<?php

namespace App\Enums\Strategy;

final class OssOption
{
    /** @var string 存取位址 */
    const Url = 'url';

    /** @var string AccessKeyId */
    const AccessKeyId = 'access_key_id';

    /** @var string AccessKeySecret */
    const AccessKeySecret = 'access_key_secret';

    /** @var string Endpoint */
    const Endpoint = 'endpoint';

    /** @var string 儲存桶名稱 */
    const Bucket = 'bucket';
}
