<?php

namespace App\Enums\Watermark;

final class ImageOption
{
    /** @var string 浮水印圖片 */
    const Image = 'image';

    /** @var string 浮水印位置 */
    const Position = 'position';

    /** @var string 浮水印圖片寬 */
    const Width = 'width';

    /** @var string 浮水印圖片高 */
    const Height = 'height';

    /** @var string 旋轉角度 */
    const Rotate = 'rotate';

    /** @var string 不透明度 */
    const Opacity = 'opacity';

    /** @var string X軸偏移量 */
    const X = 'x';

    /** @var string Y軸偏移量 */
    const Y = 'y';
}
