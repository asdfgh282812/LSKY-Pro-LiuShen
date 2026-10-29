<?php

namespace App\Http\Controllers;

use App\Enums\GroupConfigKey;
use App\Enums\UserStatus;
use App\Enums\Watermark\Mode;
use App\Exceptions\UploadException;
use App\Http\Result;
use App\Models\Group;
use App\Models\Image;
use App\Models\Strategy;
use App\Models\User;
use App\Services\ImageService;
use App\Utils;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Intervention\Image\Facades\Image as InterventionImage;
use League\Flysystem\FilesystemException;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Result;

    protected function forbidden(?string $message = null): Response
    {
        return $this->response(false, $message ?: config('token_abilities.forbidden_message'), [], 403);
    }

    public function install(Request $request): View|Response
    {
        if (file_exists(base_path('installed.lock'))) {
            if ($request->expectsJson()) {
                return $this->fail('Already installed. if you want to reinstall, please remove installed.lock file.');
            }
            abort(404);
        }

        $extensions = collect([
            ['name' => 'BCMath', 'intro' => '數學精度處理擴充'],
            ['name' => 'Ctype', 'intro' => '字元類型檢測擴充'],
            ['name' => 'DOM', 'intro' => 'DOM 物件模型，用於處理文件元素'],
            ['name' => 'Fileinfo', 'intro' => '取得檔案資訊擴充'],
            ['name' => 'JSON', 'intro' => 'JavaScript 物件符號（JSON）'],
            ['name' => 'Mbstring', 'intro' => '多位元組字串處理擴充'],
            ['name' => 'OpenSSL', 'intro' => '加密擴充'],
            ['name' => 'PCRE', 'intro' => '正則表示式擴充'],
            ['name' => 'PDO', 'intro' => '資料庫擴充'],
            ['name' => 'Tokenizer', 'intro' => '令牌處理擴充'],
            ['name' => 'XML', 'intro' => 'Xml 解析器'],
            ['name' => 'Imagick', 'intro' => '高效能圖片處理擴充'],
        ])->transform(function ($item) {
            $item['result'] = extension_loaded(strtolower($item['name']));
            return $item;
        })->push([
            'name' => 'readlink、symlink 函式',
            'intro' => '讀取、建立符號連結函式',
            'result' => function_exists('readlink') && function_exists('symlink'),
        ])->push([
            'name' => 'putenv、getenv 函式',
            'intro' => '設定和取得環境變數函式',
            'result' => function_exists('putenv') && function_exists('getenv'),
        ])->push([
            'name' => 'exec、shell_exec 函式',
            'intro' => '執行外部命令',
            'result' => function_exists('exec') && function_exists('shell_exec'),
        ])->push([
            'name' => 'chmod、chown、fileperms 函式',
            'intro' => '設定和取得檔案、資料夾權限函式',
            'result' => function_exists('chmod') && function_exists('chown') && function_exists('fileperms'),
        ])->push([
            'name' => 'PHP >= 8.0.2',
            'intro' => '最低要求 PHP 8.0.2 版本',
            'result' => phpversion() >= 8,
        ]);

        $status = ! $extensions->where('result', false)->isNotEmpty();

        if ($request->method() === 'POST') {
            try {
                $request->validate([
                    'account.email' => 'required|email',
                    'account.password' => 'required|between:6,32'
                ], [], [
                    'account.email' => '管理員帳號電子郵件',
                    'account.password' => '管理員帳號密碼'
                ]);

                $data = collect($request->only([
                    'connection', 'host', 'port', 'database', 'username', 'password',
                ]))->transform(fn($item, $key) => ['--'.$key => $item])->collapse();
                $output = new BufferedOutput();
                $exitCode = Artisan::call('lsky:install', $data->toArray(), $output);
                if ($exitCode) {
                    throw new \Exception(str_replace(PHP_EOL, '<br/>', $output->fetch()));
                }
                $user = new User([
                    'name' => '超級管理員',
                    'email' => $request->input('account.email'),
                    'password' => Hash::make($request->input('account.password')),
                    'registered_ip' => $request->ip(),
                ]);
                $user->group_id = Group::query()->first()['id'];
                $user->is_adminer = true;
                $user->status = UserStatus::Normal;
                $user->email_verified_at = date('Y-m-d H:i:s');
                Strategy::query()->update(['configs->url' => $request->getSchemeAndHttpHost().'/i']);
                $user->save();
            } catch (\Throwable $e) {
                @unlink(base_path('installed.lock'));
                Utils::e($e, '執行安裝程式時出現異常');
                return $this->fail($e->getMessage());
            }
            return $this->success();
        }

        return view('install', compact('extensions', 'status'));
    }

    public function upload(Request $request, ImageService $service): Response
    {
        try {
            $image = $service->store($request);
        } catch (UploadException $e) {
            return $this->fail($e->getMessage());
        } catch (\Throwable $e) {
            Utils::e($e, 'Web 上傳檔案時發生異常');
            if (config('app.debug')) {
                return $this->fail($e->getMessage());
            }
            return $this->fail('服務異常，請稍後再試');
        }
        return $this->success('上傳成功', $image->setAppends(['pathname', 'links'])->only(
            'id', 'pathname', 'origin_name', 'size', 'mimetype', 'md5', 'sha1', 'links'
        ));
    }

    public function output(Request $request, ImageService $service): StreamedResponse
    {
        /** @var Image $image */
        $image = Image::query()
            ->with('group')
            ->where('key', $request->route('key'))
            ->where('extension', strtolower($request->route('extension')))
            ->firstOr(fn() => abort(404));
        if (! $image->group?->configs->get(GroupConfigKey::IsEnableOriginalProtection)) {
            abort(404);
        }
        try {
            $cacheKey = "image_{$image->key}";

            if (Cache::has($cacheKey)) {
                $contents = Cache::get($cacheKey);
            } else {
                $contents = $image->filesystem()->read($image->pathname);
                $configs = collect($image->group?->configs->get(GroupConfigKey::WatermarkConfigs));

                if (
                    $image->group?->configs->get(GroupConfigKey::IsEnableWatermark) &&
                    $configs->get('mode', Mode::Overlay) == Mode::Dynamic &&
                    ! in_array($image->extension, ['ico', 'gif', 'svg'])
                ) {
                    $quality = $image->group?->configs->get(GroupConfigKey::ImageSaveQuality, 75);
                    $contents = $service->stickWatermark($contents, $configs)->encode($image->extension, $quality)->getEncoded();
                }
                $cacheTtl = (int)$image->group?->configs->get(GroupConfigKey::ImageCacheTtl, 0);
                if ($cacheTtl) {
                    Cache::remember($cacheKey, $cacheTtl, fn () => $contents);
                } else {
                    if (Cache::has($cacheKey)) Cache::forget($cacheKey);
                }
            }
        } catch (FilesystemException $e) {
            Utils::e($e, '圖片輸出時出現異常');
            abort(404);
        }

        $mimetype = $image->mimetype;

        if (in_array($image->extension, ['ico', 'svg'])) {
            goto out;
        }

        if (in_array($image->extension, ['psd', 'tif', 'bmp'])) {
            $mimetype = 'image/png';
            $contents = InterventionImage::make($contents)->encode('png')->getEncoded();
        }

        out:

        return \response()->stream(function () use ($contents) {
            echo $contents;
        }, headers: ['Content-type' => $mimetype]);
    }
}
