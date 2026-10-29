<?php

namespace App\Enums;

final class GroupConfigKey
{
    /** @var string 最大檔案大小 */
    const MaximumFileSize = 'maximum_file_size';

    /** @var string 併發上傳數量 */
    const ConcurrentUploadNum = 'concurrent_upload_num';

    /** @var string 上傳是否需要審查 */
    const IsEnableScan = 'is_enable_scan';

    /** @var string 審查設定 */
    const ScanConfigs = 'scan_configs';

    /** @var string 違規圖片審查後動作 */
    const ScannedAction = 'scanned_action';

    /** @var string 是否啟用原圖保護功能 */
    const IsEnableOriginalProtection = 'is_enable_original_protection';

    /** @var string 上傳是否啟用浮水印功能 */
    const IsEnableWatermark = 'is_enable_watermark';

    /** @var string 浮水印設定 */
    const WatermarkConfigs = 'watermark_configs';

    /** @var string 每分鐘上傳限制 */
    const LimitPerMinute = 'limit_per_minute';

    /** @var string 每小時上傳限制 */
    const LimitPerHour = 'limit_per_hour';

    /** @var string 每天上傳數量限制 */
    const LimitPerDay = 'limit_per_day';

    /** @var string 每週上傳數量限制 */
    const LimitPerWeek = 'limit_per_week';

    /** @var string 每月上傳數量限制 */
    const LimitPerMonth = 'limit_per_month';

    /** @var string 允許上傳的副檔名 */
    const AcceptedFileSuffixes = 'accepted_file_suffixes';

    /** @var string 路徑命名規則 */
    const PathNamingRule = 'path_naming_rule';

    /** @var string 檔案命名規則 */
    const FileNamingRule = 'file_naming_rule';

    /** @var string 圖片快取時間 */
    const ImageCacheTtl = 'image_cache_ttl';

    /** @var string 圖片儲存格式 */
    const ImageSaveFormat = 'image_save_format';

    /** @var string 圖片儲存品質 */
    const ImageSaveQuality = 'image_save_quality';
}
