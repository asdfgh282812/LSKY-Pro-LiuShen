<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $requiredIfReview = function ($driver) {
            return Rule::requiredIf($this->input('configs.is_enable_scan') && $this->input('configs.scan_configs.driver') === $driver);
        };

        $requiredIfWatermark = function ($driver) {
            return Rule::requiredIf($this->input('configs.is_enable_watermark') && $this->input('configs.watermark_configs.driver') === $driver);
        };

        return [
            'name' => 'required|between:2,30',
            'is_default' => 'boolean',
            'is_guest' => 'boolean',
            'configs' => 'required|array',
            'configs.maximum_file_size' => 'required|numeric',
            'configs.concurrent_upload_num' => 'required|integer',
            'configs.limit_per_minute' => 'required|integer',
            'configs.limit_per_hour' => 'required|integer',
            'configs.limit_per_day' => 'required|integer',
            'configs.limit_per_week' => 'required|integer',
            'configs.limit_per_month' => 'required|integer',
            'configs.image_save_quality' => 'required|min:1|max:100',
            'configs.image_save_format' => '',
            'configs.path_naming_rule' => 'max:400',
            'configs.file_naming_rule' => 'max:400',
            'configs.accepted_file_suffixes' => 'required|array|in:jpeg,jpg,png,gif,tif,bmp,ico,psd,webp,svg',

            'configs.is_enable_scan' => 'boolean',
            'configs.scanned_action' => [
                'exclude_if:configs.is_enable_scan,false',
                'in:mark,delete',
            ],
            'configs.scan_configs.driver' => ['exclude_if:configs.is_enable_scan,false', 'in:tencent,aliyun,nsfwjs'],
            'configs.scan_configs.drivers.tencent.endpoint' => [$requiredIfReview('tencent')],
            'configs.scan_configs.drivers.tencent.secret_id' => [$requiredIfReview('tencent')],
            'configs.scan_configs.drivers.tencent.secret_key' => [$requiredIfReview('tencent')],
            'configs.scan_configs.drivers.tencent.region' => [$requiredIfReview('tencent')],
            'configs.scan_configs.drivers.tencent.biz_type' => '',

            'configs.scan_configs.drivers.aliyun.access_key_id' => [$requiredIfReview('aliyun')],
            'configs.scan_configs.drivers.aliyun.access_key_secret' => [$requiredIfReview('aliyun')],
            'configs.scan_configs.drivers.aliyun.region_id' => [$requiredIfReview('aliyun')],
            'configs.scan_configs.drivers.aliyun.biz_type' => '',
            'configs.scan_configs.drivers.aliyun.scenes' => [$requiredIfReview('aliyun'), 'array'],

            'configs.scan_configs.drivers.nsfwjs.api_url' => [$requiredIfReview('nsfwjs')],
            'configs.scan_configs.drivers.nsfwjs.attr_name' => [$requiredIfReview('nsfwjs'), 'nullable'],
            'configs.scan_configs.drivers.nsfwjs.threshold' => [$requiredIfReview('nsfwjs'), 'nullable', 'integer', 'between:1,100'],

            'configs.is_enable_original_protection' => 'boolean',
            'configs.image_cache_ttl' => 'nullable|numeric',

            'configs.is_enable_watermark' => 'boolean',
            'configs.watermark_configs.mode' => ['in:1,2'],
            'configs.watermark_configs.driver' => ['exclude_if:configs.is_enable_watermark,false', 'in:font,image'],
            'configs.watermark_configs.drivers.font.font' => [
                $requiredIfWatermark('font'),
                function ($attribute, $value, $fail) {
                    if (! file_exists(storage_path('app/public/'.$value))) {
                        $fail('字型檔案不存在');
                    }
                },
            ],
            'configs.watermark_configs.drivers.font.position' => [$requiredIfWatermark('font')],
            'configs.watermark_configs.drivers.font.text' => [$requiredIfWatermark('font')],
            'configs.watermark_configs.drivers.font.color' => [$requiredIfWatermark('font')],
            'configs.watermark_configs.drivers.font.size' => [$requiredIfWatermark('font'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.font.angle' => [$requiredIfWatermark('font'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.font.x' => [$requiredIfWatermark('font'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.font.y' => [$requiredIfWatermark('font'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.image' => [
                $requiredIfWatermark('image'),
                function ($attribute, $value, $fail) {
                    if (! file_exists(storage_path('app/public/'.$value))) {
                        $fail('圖片檔案不存在');
                    }
                },
            ],
            'configs.watermark_configs.drivers.image.position' => [$requiredIfWatermark('image')],
            'configs.watermark_configs.drivers.image.width' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.height' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.opacity' => [
                $requiredIfWatermark('image'), 'nullable', 'integer', 'between:0,100',
            ],
            'configs.watermark_configs.drivers.image.rotate' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.x' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
            'configs.watermark_configs.drivers.image.y' => [$requiredIfWatermark('image'), 'nullable', 'integer'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => '組名稱',
            'is_default' => '是否預設',
            'configs' => '組設定',
            'configs.maximum_file_size' => '檔案最大上傳大小',
            'configs.concurrent_upload_num' => '併發上傳數量',
            'configs.limit_per_minute' => '每分鐘上傳限制',
            'configs.limit_per_hour' => '每小時上傳限制',
            'configs.limit_per_day' => '每天上傳限制',
            'configs.limit_per_week' => '每週上傳限制',
            'configs.limit_per_month' => '每月上傳限制',
            'configs.path_naming_rule' => '路徑命名規則',
            'configs.file_naming_rule' => '檔案命名規則',
            'configs.image_save_quality' => '圖片儲存品質',
            'configs.image_save_format' => '圖片儲存格式',
            'configs.accepted_file_suffixes' => '允許上傳的副檔名',

            'configs.is_enable_scan' => '是否啟用圖片審核',
            'configs.scanned_action' => '圖片審核動作',
            'configs.scan_configs.driver' => '圖片審核驅動',
            'configs.scan_configs.drivers.tencent.endpoint' => 'Endpoint',
            'configs.scan_configs.drivers.tencent.secret_id' => 'SecretId',
            'configs.scan_configs.drivers.tencent.secret_key' => 'SecretKey',
            'configs.scan_configs.drivers.tencent.region' => '地域節點',
            'configs.scan_configs.drivers.tencent.biz_type' => '業務場景',
            'configs.scan_configs.drivers.aliyun.access_key_id' => 'AccessKeyId',
            'configs.scan_configs.drivers.aliyun.access_key_secret' => 'AccessKeySecret',
            'configs.scan_configs.drivers.aliyun.region_id' => '地域節點',
            'configs.scan_configs.drivers.aliyun.biz_type' => '場景名稱',
            'configs.scan_configs.drivers.aliyun.scenes' => '審核場景',
            'configs.scan_configs.drivers.nsfwjs.api_url' => 'API 位址',
            'configs.scan_configs.drivers.nsfwjs.attr_name' => '表單名稱',
            'configs.scan_configs.drivers.nsfwjs.threshold' => '閾值',

            'configs.is_enable_original_protection' => '是否啟用原圖保護功能',
            'configs.image_cache_ttl' => '圖片快取時間',

            'configs.is_enable_watermark' => '是否啟用浮水印功能',
            'configs.watermark_configs.driver' => '浮水印驅動',
            'configs.watermark_configs.drivers.font.font' => '字型檔案',
            'configs.watermark_configs.drivers.font.position' => '浮水印位置',
            'configs.watermark_configs.drivers.font.text' => '浮水印文字',
            'configs.watermark_configs.drivers.font.color' => '字型顏色',
            'configs.watermark_configs.drivers.font.size' => '浮水印文字大小',
            'configs.watermark_configs.drivers.font.angle' => '浮水印旋轉角度',
            'configs.watermark_configs.drivers.font.x' => '浮水印X軸偏移量',
            'configs.watermark_configs.drivers.font.y' => '浮水印Y軸偏移量',
            'configs.watermark_configs.drivers.image.image' => '浮水印圖片檔案',
            'configs.watermark_configs.drivers.image.position' => '浮水印位置',
            'configs.watermark_configs.drivers.image.width' => '浮水印圖片寬度',
            'configs.watermark_configs.drivers.image.height' => '浮水印圖片高度',
            'configs.watermark_configs.drivers.image.opacity' => '浮水印透明度',
            'configs.watermark_configs.drivers.image.rotate' => '浮水印旋轉角度',
            'configs.watermark_configs.drivers.image.x' => '浮水印X軸偏移量',
            'configs.watermark_configs.drivers.image.y' => '浮水印Y軸偏移量',
        ];
    }
}
