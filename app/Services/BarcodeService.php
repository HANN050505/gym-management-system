<?php

namespace App\Services;

class BarcodeService
{
    /**
     * Code 128 B patterns
     */
    private static array $patterns = [
        "212222", "222122", "222221", "121223", "121322", "131222", "122213", "122312", "132212", "221213",
        "221312", "231212", "112232", "122132", "122231", "113222", "123122", "123221", "223211", "221132",
        "221231", "213212", "223112", "312131", "311222", "321122", "321221", "312212", "322112", "322211",
        "212123", "212321", "232121", "111323", "131123", "131321", "112313", "132113", "132311", "211313",
        "231113", "231311", "112133", "112331", "132131", "113123", "113321", "133121", "313121", "211331",
        "231131", "213113", "213311", "213131", "311123", "311321", "331121", "312113", "312311", "332111",
        "314111", "221411", "431111", "111224", "111422", "121124", "121421", "141122", "141221", "112214",
        "112412", "122114", "122411", "142112", "142211", "241211", "221114", "413111", "241112", "134111",
        "111242", "121142", "121241", "114212", "124112", "124211", "411212", "421112", "421211", "212141",
        "214121", "412121", "111143", "111341", "131141", "114113", "114311", "411113", "411311", "113141",
        "114131", "311141", "411131", "211412", "211214", "211232", "2331112"
    ];

    /**
     * Generate SVG Code 128
     */
    public static function generateSvg(string $text, int $height = 50, int $barWidth = 2): string
    {
        $code = strtoupper(trim($text));
        $len = strlen($code);

        // Start B (index 104)
        $checksum = 104;
        $patternSequence = self::$patterns[104];

        for ($i = 0; $i < $len; $i++) {
            $ascii = ord($code[$i]);
            $val = $ascii - 32;
            if ($val < 0 || $val > 95) {
                $val = 0;
            }
            $checksum += $val * ($i + 1);
            $patternSequence .= self::$patterns[$val];
        }

        // Checksum
        $checkVal = $checksum % 103;
        $patternSequence .= self::$patterns[$checkVal];

        // Stop pattern (index 106)
        $patternSequence .= self::$patterns[106];

        // Calculate total width
        $totalModules = 0;
        for ($i = 0; $i < strlen($patternSequence); $i++) {
            $totalModules += (int)$patternSequence[$i];
        }

        $quietZone = 10;
        $svgWidth = ($totalModules + ($quietZone * 2)) * $barWidth;
        $svgHeight = $height;

        $x = $quietZone * $barWidth;
        $bars = '';
        $isBar = true;

        for ($i = 0; $i < strlen($patternSequence); $i++) {
            $width = (int)$patternSequence[$i] * $barWidth;
            if ($isBar) {
                $bars .= sprintf('<rect x="%d" y="0" width="%d" height="%d" fill="#000000" />', $x, $width, $height);
            }
            $x += $width;
            $isBar = !$isBar;
        }

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 %d %d" width="100%%" height="%d" preserveAspectRatio="none">%s</svg>',
            $svgWidth,
            $svgHeight,
            $svgHeight,
            $bars
        );
    }
}

