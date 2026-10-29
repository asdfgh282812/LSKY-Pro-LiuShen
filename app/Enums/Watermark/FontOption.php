<?php

namespace App\Enums\Watermark;

final class FontOption
{
    /** @var string 文字內容 */
    const Text = 'text';

    /** @var string 文字顏色 */
    const Color = 'color';

    /** @var string 字型大小 */
    const Size = 'size';

    /** @var string 字型檔案路徑 */
    const Font = 'font';

    /** @var string 浮水印位置 */
    const Position = 'position';

    /** @var string 旋轉角度 */
    const Angle = 'angle';

    /** @var string X軸偏移量 */
    const X = 'x';

    /** @var string Y軸偏移量 */
    const Y = 'y';
}
