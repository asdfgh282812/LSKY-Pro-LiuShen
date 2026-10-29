<?php

namespace App\Enums;

final class UserConfigKey
{
    /** @var string 預設相簿 */
    const DefaultAlbum = 'default_album';

    /** @var string 預設策略 */
    const DefaultStrategy = 'default_strategy';

    /** @var string 預設權限 */
    const DefaultPermission = 'default_permission';

    /** @var string 圖片貼上後動作 */
    const PastedAction = 'pasted_action';

    /** @var string 上傳是否自動清除預覽 */
    const IsAutoClearPreview = 'is_auto_clear_preview';
}
