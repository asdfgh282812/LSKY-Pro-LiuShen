<?php

namespace App\Http\Requests\Admin;

use App\Enums\StrategyKey;
use App\Http\Requests\FormRequest;
use App\Models\Strategy;
use Illuminate\Support\Collection;

class StrategyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $checkUrl = function ($attribute, $value, $fail) {
            if ($this->input('key') == StrategyKey::Local) {
                $folders = [config('app.thumbnail_path'), 'fonts', 'css', 'js'];
                $symlink = Strategy::getRootPath($value);
                if (! $symlink) {
                    return $fail('存取網域缺少根路徑');
                }
                if (in_array($symlink, $folders)) {
                    return $fail('系統保留路徑：'. $symlink);
                }
                if (false !== strpbrk($symlink, "\\/?%*:|\"<>")) {
                    return $fail('根路徑名稱不符合規則');
                }
                // 修改時單獨判斷
                if ($this->method() === 'PUT') {
                    $symlinks = Strategy::query()
                        ->where('id', '<>', $this->route('id'))
                        ->pluck('configs')
                        ->transform(function (Collection $configs) {
                            return Strategy::getRootPath($configs->get('url'));
                        })->merge($folders)->values()->toArray();
                    if (in_array($symlink, $symlinks)) {
                        return $fail('根路徑已經存在');
                    }
                } else {
                    if (realpath(public_path($symlink))) {
                        return $fail('根路徑已經存在');
                    }
                }
            }
        };

        $array = [
            'groups' => 'array',
            'name' => 'required|max:60',
            'intro' => 'max:2000',
            'key' => 'required|integer',
            'configs.url' => ['required', 'url'],
            'configs.queries' => '',
        ];

        return array_merge($array, match((int)$this->input('key')) {
            StrategyKey::Local => [
                'configs.url' => ['required', 'url', $checkUrl],
                'configs.root' => ['max:1000', function ($attribute, $value, $fail) {
                    if ($value) {
                        if (! is_dir($value)) {
                            return $fail('儲存路徑不存在');
                        }
                        if (! is_writeable($value)) {
                            return $fail('儲存路徑沒有寫入權限');
                        }
                    }
                }],
            ],
            StrategyKey::S3 => [
                'configs.access_key_id' => 'required',
                'configs.secret_access_key' => 'required',
                'configs.endpoint' => '',
                'configs.region' => '',
                'configs.bucket' => 'required',
            ],
            StrategyKey::Oss => [
                'configs.access_key_id' => 'required',
                'configs.access_key_secret' => 'required',
                'configs.endpoint' => 'required',
                'configs.bucket' => 'required',
            ],
            StrategyKey::Cos => [
                'configs.app_id' => 'required',
                'configs.secret_id' => 'required',
                'configs.secret_key' => 'required',
                'configs.region' => 'required',
                'configs.bucket' => 'required',
            ],
            StrategyKey::Kodo => [
                'configs.access_key' => 'required',
                'configs.secret_key' => 'required',
                'configs.bucket' => 'required',
            ],
            StrategyKey::Uss => [
                'configs.service' => 'required',
                'configs.operator' => 'required',
                'configs.password' => 'required',
            ],
            StrategyKey::Sftp => [
                'configs.root' => 'required',
                'configs.host' => 'required',
                'configs.port' => 'integer',
                'configs.username' => 'required',
                'configs.password' => 'required_if:configs.private_key,null',
                'configs.private_key' => 'required_if:configs.password,null',
                'configs.passphrase' => '',
                'configs.use_agent' => 'required|boolean',
            ],
            StrategyKey::Ftp => [
                'configs.root' => 'required',
                'configs.host' => 'required',
                'configs.port' => 'integer',
                'configs.username' => 'required',
                'configs.password' => 'required',
                'configs.ssl' => 'required|boolean',
                'configs.passive' => 'required|boolean',
            ],
            StrategyKey::Webdav => [
                'configs.base_uri' => 'required',
                'configs.username' => '',
                'configs.password' => '',
                'configs.auth_type' => '',
                'configs.prefix' => '',
            ],
            StrategyKey::Minio => [
                'configs.access_key' => 'required',
                'configs.secret_key' => 'required',
                'configs.endpoint' => '',
                'configs.region' => '',
                'configs.bucket' => 'required',
                'configs.bucket_endpoint' => '',
            ],
        });
    }

    public function attributes()
    {
        $array = [
            'name' => '名稱',
            'intro' => '簡介',
            'key' => '策略',
            'configs.url' => '存取網址',
            'configs.queries' => 'Url 額外參數',
        ];

        return array_merge($array, match((int)$this->input('key')) {
            StrategyKey::Local => [
                'configs.root' => '根目錄路徑',
            ],
            StrategyKey::S3 => [
                'configs.access_key_id' => 'AccessKeyId',
                'configs.secret_access_key' => 'SecretAccessKey',
                'configs.endpoint' => '連線位址',
                'configs.region' => '區域',
                'configs.bucket' => '儲存桶名稱',
            ],
            StrategyKey::Oss => [
                'configs.access_key_id' => 'AccessKeyId',
                'configs.access_key_secret' => 'AccessKeySecret',
                'configs.endpoint' => '地域節點網域名稱',
                'configs.bucket' => 'Bucket 名稱',
            ],
            StrategyKey::Cos => [
                'configs.app_id' => 'AppId',
                'configs.secret_id' => 'SecretId',
                'configs.secret_key' => 'SecretKey',
                'configs.region' => '所屬地域',
                'configs.bucket' => '儲存桶名稱',
            ],
            StrategyKey::Kodo => [
                'configs.access_key' => 'AccessKey',
                'configs.secret_key' => 'SecretKey',
                'configs.bucket' => 'Bucket',
            ],
            StrategyKey::Uss => [
                'configs.service' => '服務名稱',
                'configs.operator' => '操作員名稱',
                'configs.password' => '操作員密碼',
            ],
            StrategyKey::Sftp => [
                'configs.root' => '根目錄路徑',
                'configs.host' => '主機位址',
                'configs.port' => '連線連接埠',
                'configs.username' => '使用者名稱',
                'configs.password' => '密碼',
                'configs.private_key' => '金鑰',
                'configs.passphrase' => '金鑰口令',
                'configs.use_agent' => '使用代理',
            ],
            StrategyKey::Ftp => [
                'configs.root' => '根目錄路徑',
                'configs.host' => '主機位址',
                'configs.port' => '連線連接埠',
                'configs.username' => '使用者名稱',
                'configs.password' => '密碼',
                'configs.ssl' => '加密連線',
                'configs.passive' => '被動模式',
            ],
            StrategyKey::Webdav => [
                'configs.base_uri' => '連線位址',
                'configs.username' => '使用者名稱',
                'configs.password' => '密碼',
                'configs.auth_type' => '認證方式',
                'configs.prefix' => '字首',
            ],
            StrategyKey::Minio => [
                'configs.access_key' => 'AccessKey',
                'configs.secret_key' => 'SecretKey',
                'configs.endpoint' => '連線位址',
                'configs.region' => '區域',
                'configs.bucket' => 'Bucket 名稱',
                'configs.bucket_endpoint' => 'BucketEndpoint',
            ],
        });
    }
}
