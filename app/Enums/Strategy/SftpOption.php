<?php

namespace App\Enums\Strategy;

final class SftpOption
{
    /** @var string 存取url */
    const Url = 'url';

    /** @var string 根目錄 */
    const Root = 'root';

    /** @var string 主機位址 */
    const Host = 'host';

    /** @var string 連線名稱 */
    const Username = 'username';

    /** @var string 密碼 */
    const Password = 'password';

    /** @var string 私鑰 */
    const PrivateKey = 'private_key';

    /** @var string 金鑰口令 */
    const Passphrase = 'passphrase';

    /** @var string 連線連接埠 */
    const Port = 'port';

    /** @var string 使用代理 */
    const UseAgent = 'use_agent';
}
