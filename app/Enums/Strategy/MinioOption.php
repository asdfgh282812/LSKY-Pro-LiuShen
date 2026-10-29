<?php

namespace App\Enums\Strategy;

final class MinioOption
{
    /** @var string 存取url */
    const Url = 'url';

    /** @var string AccessKey */
    const AccessKey = 'access_key';

    /** @var string SecretKey */
    const SecretKey = 'secret_key';

    /** @var string Endpoint */
    const Endpoint = 'endpoint';

    /** @var string 區域 */
    const Region = 'region';

    /** @var string Bucket */
    const Bucket = 'bucket';

    /** @var string BucketEndpoint */
    const BucketEndpoint = 'bucket_endpoint';
}
