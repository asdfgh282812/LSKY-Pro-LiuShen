<?php

namespace App\Enums\Strategy;

final class CosOption
{
    /** @var string 存取位址 */
    const Url = 'url';

    /** @var string AppId */
    const AppId = 'app_id';

    /** @var string SecretId */
    const SecretId = 'secret_id';

    /** @var string SecretKey */
    const SecretKey = 'secret_key';

    /** @var string 所屬地域 */
    const Region = 'region';

    /** @var string 儲存桶名稱 */
    const Bucket = 'bucket';
}
