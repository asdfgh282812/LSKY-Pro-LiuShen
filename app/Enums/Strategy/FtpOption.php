<?php

namespace App\Enums\Strategy;

final class FtpOption
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

    /** @var string 連線連接埠 */
    const Port = 'port';

    /** @var string 加密連線 */
    const Ssl = 'ssl';

    /** @var string 被動模式 */
    const Passive = 'passive';
}
