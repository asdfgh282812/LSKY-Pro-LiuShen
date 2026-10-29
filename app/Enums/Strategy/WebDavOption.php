<?php

namespace App\Enums\Strategy;

final class WebDavOption
{
    /** @var string 存取url */
    const Url = 'url';

    /** @var string baseUri */
    const BaseUri = 'base_uri';

    /** @var string 使用者名稱 */
    const Username = 'username';

    /** @var string 密碼 */
    const Password = 'password';

    /** @var string 認證方式 */
    const AuthType = 'auth_type';

    /** @var string 位址字首 */
    const Prefix = 'prefix';
}
