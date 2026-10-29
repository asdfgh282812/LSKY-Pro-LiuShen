<?php

namespace App\Services;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Green\Green;
use App\Enums\ConfigKey;
use App\Enums\GroupConfigKey;
use App\Enums\ImagePermission;
use App\Enums\Scan\AliyunOption;
use App\Enums\Scan\NsfwJsOption;
use App\Enums\Scan\TencentOption;
use App\Enums\Strategy\CosOption;
use App\Enums\Strategy\FtpOption;
use App\Enums\Strategy\KodoOption;
use App\Enums\Strategy\LocalOption;
use App\Enums\Strategy\MinioOption;
use App\Enums\Strategy\OssOption;
use App\Enums\Strategy\S3Option;
use App\Enums\Strategy\SftpOption;
use App\Enums\Strategy\UssOption;
use App\Enums\Strategy\WebDavOption;
use App\Enums\StrategyKey;
use App\Enums\UserConfigKey;
use App\Enums\UserStatus;
use App\Enums\Watermark\FontOption;
use App\Enums\Watermark\ImageOption;
use App\Enums\Watermark\Mode;
use App\Exceptions\UploadException;
use App\Models\Group;
use App\Models\Image;
use App\Models\Strategy;
use App\Models\User;
use App\Utils;
use Aws\S3\S3Client;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as InterventionImage;
use Intervention\Image\Imagick\Font;
use Intervention\Image\ImageManager;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\FilesystemException;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\WebDAV\WebDAVAdapter;
use Overtrue\Flysystem\Cos\CosAdapter;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;
use Sabre\DAV\Client;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Ims\V20201229\ImsClient;
use TencentCloud\Ims\V20201229\Models\ImageModerationRequest;
use WispX\Flysystem\Upyun\UpyunAdapter;
use Zing\Flysystem\Oss\OssAdapter;
use OSS\OssClient;

class ImageService
{
    /**
     * @param  Request  $request
     * @return Image
     * @throws UploadException
     */
    public function store(Request $request): Image
    {
        $file = $request->file('file');

        if (Auth::guest() && !Utils::config(ConfigKey::IsAllowGuestUpload, true)) {
            throw new UploadException('管理員關閉了訪客上傳');
        }

        $image = new Image();
        /** @var User|null $user */
        $user = $request->user();

        /** @var Group $group */
        $group = ! is_null($user) ? $user->group : Group::query()->where('is_guest', true)->first();
        // 組設定
        $configs = $group->configs;
        // 儲存策略
        $strategies = $group->strategies()->get();
        if ($strategies->isEmpty()) {
            throw new UploadException('沒有可用的儲存，請聯絡管理員。');
        }

        $extension = strtolower($file->getClientOriginalExtension());

        if (! in_array($extension, $configs->get(GroupConfigKey::AcceptedFileSuffixes))) {
            throw new UploadException('不支援的檔案類型');
        }

        if ($file->getSize() / 1024 > $configs->get(GroupConfigKey::MaximumFileSize)) {
            throw new UploadException("圖片大小超出限制");
        }

        // 圖片所屬組
        $image->group_id = $group->id;
        // 圖片預設權限
        $image->permission = ImagePermission::Private;

        if ($request->has('strategy_id')) {
            if (! $strategy = $strategies->find($request->input('strategy_id'))) {
                throw new UploadException('選定的策略不存在');
            }
        } else {
            // 沒有指定則選擇第一個策略
            $strategy = $strategies->first();
        }

        $image->strategy_id = $strategy->id;

        // 預設儲存策略
        if (! is_null($user)) {
            if (Utils::config(ConfigKey::IsUserNeedVerify) && ! $user->email_verified_at) {
                throw new UploadException('帳戶未驗證');
            }

            if ($user->status !== UserStatus::Normal) {
                throw new UploadException('帳號狀態異常');
            }

            if ($file->getSize() / 1024 + $user->images()->sum('size') > $user->capacity) {
                throw new UploadException('儲存空間不足');
            }

            // 圖片儲存至預設相簿(若有)
            if ($albumId = $user->configs->get(UserConfigKey::DefaultAlbum)) {
                if ($user->albums()->where('id', $albumId)->exists()) {
                    $image->album_id = $albumId;
                }
            }

            $image->user_id = $user->id;
            // 使用者設定的圖片預設權限
            $image->permission = $user->configs->get(UserConfigKey::DefaultPermission, ImagePermission::Private);
        }

        // 上傳頻率限制
        $this->rateLimiter($configs, $request);

        // 圖片處理，跳過 ico gif svg
        if (! in_array($extension, ['ico', 'gif', 'svg'])) {
            // 圖片儲存品質與格式
            $quality = $configs->get(GroupConfigKey::ImageSaveQuality, 75);
            $format = $configs->get(GroupConfigKey::ImageSaveFormat);
            if ($quality < 100 || $format) {
                // 取得擴充名，判斷是否需要轉換
                $format = $format ?: $extension;
                $filename = Str::replaceLast($extension, $format, $file->getClientOriginalName());
                $handleImage = InterventionImage::make($file)->save('tmp_' . md5_file($file->getRealPath()), $quality);
                $file = new UploadedFile($handleImage->basePath(), $filename, $handleImage->mime());
                // 重新設定擴充名
                $extension = $format;
                $handleImage->destroy();
            }

            // 是否啟用浮水印，覆蓋原圖片
            if (
                $configs->get(GroupConfigKey::IsEnableWatermark) &&
                collect($configs->get(GroupConfigKey::WatermarkConfigs))->get('mode', Mode::Overlay) == Mode::Overlay
            ) {
                $watermarkImage = $this->stickWatermark($file, collect($configs->get(GroupConfigKey::WatermarkConfigs)));
                $watermarkImage->save();
                $file = new UploadedFile($watermarkImage->basePath(), $file->getClientOriginalName(), $file->getMimeType());
                $watermarkImage->destroy();
            }
        }

        $filename = $this->replacePathname(
            $configs->get(GroupConfigKey::PathNamingRule).'/'.$configs->get(GroupConfigKey::FileNamingRule), $file,
        );
        $pathname = $filename.".{$extension}";

        [$width, $height] = @getimagesize($file->getRealPath()) ?: [400, 400];

        $image->fill([
            'md5' => md5_file($file->getRealPath()),
            'sha1' => sha1_file($file->getRealPath()),
            'path' => $configs->get(GroupConfigKey::PathNamingRule) ? dirname($pathname) : '',
            'name' => basename($pathname),
            'origin_name' => $file->getClientOriginalName(),
            'size' => $file->getSize() / 1024,
            'mimetype' => $file->getMimeType(),
            'extension' => strtolower($extension),
            'width' => $width,
            'height' => $height,
            'is_unhealthy' => false,
            'uploaded_ip' => $request->ip(),
        ]);

        $filesystem = new Filesystem($this->getAdapter($strategy));

        // 檢測該策略是否存在該圖片，有則只建立記錄不儲存檔案
        /** @var Image $existing */
        $existing = Image::query()->when($image->strategy_id, function (Builder $builder, $id) {
            $builder->where('strategy_id', $id);
        })->where('md5', $image->md5)->where('sha1', $image->sha1)->first();
        if (is_null($existing)) {
            $handle = fopen($file, 'r');
            try {
                $filesystem->writeStream($pathname, $handle);
            } catch (FilesystemException $e) {
                Utils::e($e, '儲存圖片時出現異常');
                throw new UploadException(config('app.debug', false) ? $e->getMessage() : '圖片上傳失敗');
            }
            if (is_resource($handle)) @fclose($handle);
        } else {
            $image->fill($existing->only('path', 'name'));
        }

        // 增加當前使用者的圖片數量和相簿圖片數量
        $updateNum = function ($method) use ($user, $image) {
            DB::transaction(function () use ($method, $user, $image) {
                if (! is_null($user)) {
                    $user->{$method}('image_num');
                }
                if (! is_null($image->album)) {
                    $image->album->{$method}('image_num');
                }
            });
        };

        try {
            // 圖片記錄儲存失敗，刪除物理檔案
            if (! $image->save()) {
                if (is_null($existing)) $filesystem->delete($image->pathname);
                throw new \Exception('圖片記錄儲存失敗');
            }
            $updateNum('increment');
        } catch (\Throwable $e) {
            Utils::e($e, '儲存圖片記錄時出現異常');
            throw new UploadException('圖片記錄儲存失敗');
        }

        // 圖片檢測，跳過 tif、ico、psd、svg 格式
        if ($configs->get(GroupConfigKey::IsEnableScan) && ! in_array($extension, ['psd', 'ico', 'tif', 'svg'])) {
            $scanConfigs = $configs->get(GroupConfigKey::ScanConfigs);
            if ($this->scan(
                driver: $scanConfigs['driver'],
                configs: collect($scanConfigs['drivers'][$scanConfigs['driver']]),
                image: $image,
                file: $file,
            )) {
                // 標記 or 刪除
                if ($configs->get(GroupConfigKey::ScannedAction) === 'delete') {
                    $image->delete();
                    $updateNum('decrement');
                    throw new UploadException('圖片涉嫌違規，禁止上傳。');
                } else {
                    $image->is_unhealthy = true;
                    $image->save();
                }
            }
        }

        $this->makeThumbnail($image, $file);

        // 上傳完成後刪除暫存檔案
        unlink($file->getPathname());

        return $image;
    }

    public function getAdapter(Strategy $strategy): FilesystemAdapter
    {
        $configs = $strategy->configs;
        return match ($strategy->key) {
            StrategyKey::Local => new LocalFilesystemAdapter(
                location: $configs->get(LocalOption::Root) ?: config('filesystems.disks.uploads.root')
            ),
            StrategyKey::S3 => new AwsS3V3Adapter(
                client: new S3Client([
                    'credentials' => [
                        'key'    => $configs->get(S3Option::AccessKeyId),
                        'secret' => $configs->get(S3Option::SecretAccessKey)
                    ],
                    'endpoint' => $configs->get(S3Option::Endpoint),
                    'region' => $configs->get(S3Option::Region),
                    'version' => '2006-03-01',
                ]),
                bucket: $configs->get(S3Option::Bucket),
            ),
            StrategyKey::Oss => new OssAdapter(
                client: new OssClient($configs->get(
                    OssOption::AccessKeyId),
                    $configs->get(OssOption::AccessKeySecret),
                    $configs->get(OssOption::Endpoint),
                ),
                bucket: $configs->get(OssOption::Bucket),
            ),
            StrategyKey::Cos => new CosAdapter($configs->only([
                CosOption::AppId, CosOption::SecretId, CosOption::SecretKey, CosOption::Region, CosOption::Bucket,
            ])->toArray()),
            StrategyKey::Kodo => new QiniuAdapter(
                accessKey: $configs->get(KodoOption::AccessKey),
                secretKey: $configs->get(KodoOption::SecretKey),
                bucket: $configs->get(KodoOption::Bucket),
                domain: $configs->get(KodoOption::Url),
            ),
            StrategyKey::Uss => new UpyunAdapter(
                service: $configs->get(UssOption::Service),
                operator: $configs->get(UssOption::Operator),
                password: $configs->get(UssOption::Password),
                domain: $configs->get(UssOption::Url),
            ),
            StrategyKey::Sftp => new SftpAdapter(
                connectionProvider: new SftpConnectionProvider(
                    host: $configs->get(SftpOption::Host),
                    username: $configs->get(SftpOption::Username),
                    password: $configs->get(SftpOption::Password),
                    privateKey: $configs->get(SftpOption::PrivateKey),
                    passphrase: (string)$configs->get(SftpOption::Passphrase),
                    port: (int)$configs->get(SftpOption::Port),
                    useAgent: (bool)$configs->get(SftpOption::UseAgent)
                ),
                root: $configs->get(SftpOption::Root),
            ),
            StrategyKey::Ftp => new FtpAdapter(
                connectionOptions: FtpConnectionOptions::fromArray([
                    'host' => $configs->get(FtpOption::Host),
                    'root' => $configs->get(FtpOption::Root),
                    'port' => (int)$configs->get(FtpOption::Port),
                    'ssl' => (bool)$configs->get(FtpOption::Ssl),
                    'username' => $configs->get(FtpOption::Username),
                    'password' => $configs->get(FtpOption::Password),
                    'passive' => (bool)$configs->get(FtpOption::Passive),
                    'timeout' => 30,
                ]),
            ),
            StrategyKey::Webdav => new WebDAVAdapter(new Client(([
                'baseUri' => $configs->get(WebDavOption::BaseUri),
                'userName' => $configs->get(WebDavOption::Username),
                'password' => $configs->get(WebDavOption::Password),
                'authType' => (int)$configs->get(WebDavOption::AuthType),
            ])), $configs->get(WebDavOption::Prefix) ?: ''),
            StrategyKey::Minio => new AwsS3V3Adapter(
                client: new S3Client([
                    'credentials' => [
                        'key'    => $configs->get(MinioOption::AccessKey),
                        'secret' => $configs->get(MinioOption::SecretKey)
                    ],
                    'endpoint' => $configs->get(MinioOption::Endpoint),
                    'region' => $configs->get(MinioOption::Region),
                    'version' => '2006-03-01',
                    'bucket_endpoint' => (bool)$configs->get(MinioOption::BucketEndpoint),
                ]),
                bucket: $configs->get(MinioOption::Bucket),
            ),
        };
    }

    /**
     * @throws UploadException
     */
    protected function rateLimiter(Collection $configs, Request $request)
    {
        /** @var User|null $user */
        $user = $request->user();
        $carbon = Carbon::now();
        $array = [
            'minute' => ['key' => GroupConfigKey::LimitPerMinute, 'str' => '分鐘'],
            'hours' => ['key' => GroupConfigKey::LimitPerHour, 'str' => '小時'],
            'days' => ['key' => GroupConfigKey::LimitPerDay, 'str' => '天'],
            'weeks' => ['key' => GroupConfigKey::LimitPerWeek, 'str' => '周'],
            'months' => ['key' => GroupConfigKey::LimitPerMonth, 'str' => '月'],
        ];

        foreach ($array as $key => $item) {
            $value = $configs->get($item['key'], 0);
            $count = Image::query()->whereBetween('created_at', [
                $carbon->parse("-1 {$key}"), $carbon,
            ])->when(! is_null($user), function (Builder $builder) use ($user) {
                $builder->where('user_id', $user->id);
            }, function (Builder $builder) use ($request) {
                $builder->where('uploaded_ip', $request->ip())->whereNull('user_id');
            })->count();

            if ($value && $count >= $value) {
                throw new UploadException("每{$item['str']}內你最多可以上傳 {$value} 張圖片");
            }
        }
    }

    /**
     * 檢測圖片是否違規
     *
     * @param $driver
     * @param  Collection  $configs
     * @param  Image  $image
     * @param  UploadedFile $file
     * @return bool true=違規
     * @throws UploadException
     */
    public function scan($driver, Collection $configs, Image $image, UploadedFile $file): bool
    {
        $flag = false;
        try {
            if ($driver === 'tencent') {
                // 圖片大小不得超過 5mb
                if ($file->getSize() >= 5242880) {
                    return false;
                }

                $cred = new Credential($configs->get(TencentOption::SecretId), $configs->get(TencentOption::SecretKey));
                $httpProfile = new HttpProfile();
                $httpProfile->setEndpoint($configs->get(TencentOption::Endpoint));
                $clientProfile = new ClientProfile();
                $clientProfile->setHttpProfile($httpProfile);
                $client = new ImsClient($cred, $configs->get(TencentOption::Region), $clientProfile);
                $req = new ImageModerationRequest();
                $params = [
                    "FileContent" => base64_encode($file->getContent()),
                ];
                if ($configs->get(TencentOption::BizType)) {
                    $params['BizType'] = $configs->get(TencentOption::BizType);
                }
                $req->fromJsonString(json_encode($params));
                $resp = $client->ImageModeration($req);

                if ($resp->getSuggestion() === 'Block') {
                    $flag = true;
                }
            }

            if ($driver === 'aliyun') {
                // 20 mb以內、寬高不超過 30000px
                if ($file->getSize() >= 20971520 || $image->width >= 30000 || $image->height >= 30000) {
                    return false;
                }

                AlibabaCloud::accessKeyClient(
                    $configs->get(AliyunOption::AccessKeyId),
                    $configs->get(AliyunOption::AccessKeySecret),
                )->regionId($configs->get(AliyunOption::RegionId))->asDefaultClient();
                $task = ['dataId' => $image->id, 'url' => $image->url];
                $response = Green::v20180509()->imageSyncScan()->body(json_encode([
                    'tasks' => [$task],
                    'scenes' => $configs->get(AliyunOption::Scenes),
                    'bizType' => $configs->get(AliyunOption::BizType)
                ]))->request();
                if (200 != $response->get('code')) {
                    throw new \Exception("detect not success. code:" . $response->get('code'));
                }
                if ($result = $response->data[0] ?? null) {
                    if (200 == $result->code) {
                        foreach ($result->results as $item) {
                            if ($item->suggestion === 'block') {
                                $flag = true;
                            }
                        }
                    }
                }
            }

            if ($driver === 'nsfwjs') {
                // 不支援 bmp 格式
                if ($image->extension === 'bmp') {
                    return false;
                }
                $response = Http::timeout(180)->withOptions(['timeout' => 180])->attach(
                    $configs->get(NsfwJsOption::AttrName, 'image'), $file->getContent(), $file->getClientOriginalName(),
                )->post($configs->get(NsfwJsOption::ApiUrl));
                $ratio = $configs->get(NsfwJsOption::Threshold, 60) / 100;

                if ($response->json('hentai', 0.00) >= $ratio) {
                    $flag = true;
                }

                if ($response->json('porn', 0.00) >= $ratio) {
                    $flag = true;
                }

                if ($response->json('sexy', 0.00) >= $ratio) {
                    $flag = true;
                }
            }
        } catch (\Throwable $e) {
            throw new UploadException('Scan: '.$e->getMessage());
        }

        return $flag;
    }

    /**
     * 合成浮水印
     *
     * @param  mixed  $image
     * @param  Collection  $configs
     * @return \Intervention\Image\Image
     */
    public function stickWatermark(mixed $image, Collection $configs): \Intervention\Image\Image
    {
        $driver = $configs->get('driver');
        $options = collect($configs->get("drivers")[$driver]);
        $image = InterventionImage::make($image);

        $position = $options->get(FontOption::Position, 'bottom-right');
        $offsetX = (int) $options->get(FontOption::X, 10);
        $offsetY = (int) $options->get(FontOption::Y, 10);

        $watermark = $this->getWatermark($driver, $options);

        // 原圖寬高
        $imageWidth = $image->width();
        $imageHeight = $image->height();

        // 浮水印畫布寬高
        $watermarkWidth = $watermark->width();
        $watermarkHeight = $watermark->height();

        if ($position === 'tiled') {
            // 平鋪浮水印
            for ($x = 0; $x < $imageWidth; $x++) {
                for ($y = 0; $y < $imageHeight; $y++) {
                    $image->insert($watermark, '', $x, $y);
                    $y += $watermarkHeight + $offsetY;
                }
                $x += $watermarkWidth + $offsetX;
            }
        } else {
            $image->insert($watermark, $position, $offsetX, $offsetY);
        }

        return $image;
    }

    /**
     * 生成縮圖，縮圖目錄必須在 public 目錄下，且該目錄必須存在
     *
     * @param mixed $image 圖片資料
     * @param mixed $data 物理圖片資料
     * @param int $max 最大寬高
     * @param bool $force 是否強制覆蓋
     * @return void
     */
    public function makeThumbnail(Image $image, mixed $data, int $max = 400, bool $force = false): void
    {
        $pathname = public_path($image->getThumbnailPathname());

        if (! file_exists($pathname) || $force) {
            try {
                // 建立資料夾
                if (! is_dir(dirname($pathname))) {
                    @mkdir(dirname($pathname));
                }

                // 生成縮圖，svg等格式本身體積足夠小且網頁原生支援(比生成的png縮圖還小)，不用生成縮圖，直接複製檔案
                if($image->extension ==='svg') {
                    copy($data->getPathname(), $pathname);
                }else{
                    @ini_set('memory_limit', '512M');

                    $img = InterventionImage::make($data);

                    $width = $w = $image->width;
                    $height = $h = $image->height;

                    if ($w > $max && $h > $max) {
                        $scale = min($max / $w, $max / $h);
                        $width  = (int)($w * $scale);
                        $height = (int)($h * $scale);
                    }

                    $img->fit($width, $height, fn($constraint) => $constraint->upsize())->encode('png', 60)->save($pathname);
                    $img->destroy();
                }
            } catch (\Throwable $e) {
                Utils::e($e, '生成縮圖時出現異常');
            }
        }
    }

    /**
     * 取得浮水印畫布
     *
     * @param  string  $driver
     * @param  Collection  $options
     * @return \Intervention\Image\Image
     */
    private function getWatermark(string $driver, Collection $options): \Intervention\Image\Image
    {
        $manager = new ImageManager(config('image'));

        if ($driver === 'image') {
            $watermark = $manager->make(storage_path('app/public/'.trim($options->get(ImageOption::Image), '/')));
            $opacity = (int) $options->get(ImageOption::Opacity, 0);
            $rotate = (int) $options->get(ImageOption::Rotate, 0);
            $width = $options->get(ImageOption::Width, 0);
            $height = $options->get(ImageOption::Height, 0);

            if ($opacity && $opacity != 100) {
                $watermark->opacity((int) min($opacity, 100));
            }

            if ($rotate) {
                $watermark->rotate($rotate);
            }

            if ($width + $height > 0) {
                $watermark->resize($width ?: null, $height ?: null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            return $watermark;
        } else {
            $text = $options->get(FontOption::Text, Utils::config(ConfigKey::AppName));
            $font = new Font(urldecode($text));
            $font->valign('top')
                ->file(storage_path('app/public/'.trim($options->get(FontOption::Font), '/')))
                ->size((int) $options->get(FontOption::Size, 50))
                ->angle((int) $options->get(FontOption::Angle, 0))
                ->color($options->get(FontOption::Color, '000000')); // 十六進位 or rgba
            $box = $font->getBoxSize();
            $canvas = $manager->canvas($box['width'], $box['height']);
            $font->applyToImage($canvas);
            return $manager->make($canvas);
        }
    }

    protected function replacePathname(string $pathname, UploadedFile $file): string
    {
        $array = [
            '{Y}' => date('Y'),
            '{y}' => date('y'),
            '{m}' => date('m'),
            '{d}' => date('d'),
            '{timestamp}' => time(),
            '{uniqid}' => uniqid(),
            '{md5}' => md5(microtime().Str::random()),
            '{md5-16}' => substr(md5(microtime().Str::random()), 0, 16),
            '{str-random-16}' => Str::random(),
            '{str-random-10}' => Str::random(10),
            '{filename}' => Str::replaceLast('.'.$file->getClientOriginalExtension(), '', $file->getClientOriginalName()),
            '{uid}' => Auth::check() ? Auth::id() : 0,
        ];
        return str_replace(array_keys($array), array_values($array), $pathname);
    }
}
