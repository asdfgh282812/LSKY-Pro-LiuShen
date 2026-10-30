<?php

namespace Tests\Unit;

use App\Services\ImageService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SvgSafetyTest extends TestCase
{
    public function test_plain_svg_with_embedded_raster_is_allowed()
    {
        $svg = '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"><rect width="10" height="10" fill="red"/><image href="data:image/png;base64,AAAA"/></svg>';

        $this->assertTrue((new ImageService())->isSafeSvg($svg));
    }

    #[DataProvider('unsafeSvgs')]
    public function test_executable_svg_is_rejected(string $svg)
    {
        $this->assertFalse((new ImageService())->isSafeSvg($svg));
    }

    public static function unsafeSvgs(): array
    {
        return [
            'script tag' => ['<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'],
            'spaced script' => ['<svg><  script >alert(1)</script></svg>'],
            'onload attr' => ['<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"></svg>'],
            'onclick quoted' => ['<svg><rect width="1" height="1" onclick = "alert(1)"/></svg>'],
            'javascript href' => ['<svg><a href="javascript:alert(1)"><rect width="1" height="1"/></a></svg>'],
            'entity encoded js' => ['<svg><a href="jav&#x61;script:alert(1)"><rect/></a></svg>'],
            'foreignObject' => ['<svg><foreignObject><iframe src="x"/></foreignObject></svg>'],
            'html payload' => ['<html><body><script>alert(1)</script></body></html>'],
            'xxe entity' => ['<!DOCTYPE svg [<!ENTITY x SYSTEM "file:///etc/passwd">]><svg>&x;</svg>'],
        ];
    }
}
