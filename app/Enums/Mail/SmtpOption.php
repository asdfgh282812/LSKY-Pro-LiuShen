<?php

namespace App\Enums\Mail;

final class SmtpOption
{
    const Transport = 'smtp';

    /** @var string 主機位址 */
    const Host = 'host';

    /** @var string 連線連接埠 */
    const Port = 'port';

    /** @var string 加密方式 */
    const Encryption = 'encryption';

    /** @var string 使用者名稱 */
    const Username = 'username';

    /** @var string 密碼 */
    const Password = 'password';

    /** @var string 超時時間 */
    const Timeout = 'timeout';

    /** @var string 發件人位址 */
    const FromAddress = 'from_address';

    /** @var string 發件人名稱 */
    const FromName = 'from_name';
}
