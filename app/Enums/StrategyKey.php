<?php

namespace App\Enums;

final class StrategyKey
{
    /** @var int 本地 */
    const Local = 1;

    /** @var int S3 */
    const S3 = 2;

    /** @var int 阿里雲 Oss */
    const Oss = 3;

    /** @var int 騰訊雲 Cos */
    const Cos = 4;

    /** @var int 七牛雲 Kodo */
    const Kodo = 5;

    /** @var int 又拍雲 Uss */
    const Uss = 6;

    /** @var int Sftp */
    const Sftp = 7;

    /** @var int Ftp */
    const Ftp = 8;

    /** @var int WebDav */
    const Webdav = 9;

    /** @var int Minio */
    const Minio = 10;
}
