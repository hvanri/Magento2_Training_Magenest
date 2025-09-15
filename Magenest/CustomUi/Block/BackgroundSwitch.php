<?php
namespace Magenest\CustomUi\Block;

use Magento\Framework\View\Element\Template;
use Magenest\CustomUi\Helper\Color as ColorHelper;

class BackgroundSwitch extends Template
{
    protected $colorHelper;

    public function __construct(
        Template\Context $context,
        ColorHelper $colorHelper,
        array $data = []
    ) {
        $this->colorHelper = $colorHelper;
        parent::__construct($context, $data);
    }

    public function getColorOptions(): array
    {
        return $this->colorHelper->getColorOptions();
    }

    public function getDefaultColor(): string
    {
        return $this->colorHelper->getDefaultColor();
    }
//    public function lightenColor($color, $percent) {
//        $color = ltrim($color, '#');
//        $num = hexdec($color);
//        $amt = round(2.55 * $percent);
//
//        $R = ($num >> 16) + $amt;
//        $B = ($num >> 8 & 0x00FF) + $amt;
//        $G = ($num & 0x0000FF) + $amt;
//
//        $R = ($R < 255) ? ($R < 1 ? 0 : $R) : 255;
//        $B = ($B < 255) ? ($B < 1 ? 0 : $B) : 255;
//        $G = ($G < 255) ? ($G < 1 ? 0 : $G) : 255;
//
//        return '#' . str_pad(dechex($R), 2, '0', STR_PAD_LEFT) .
//            str_pad(dechex($B), 2, '0', STR_PAD_LEFT) .
//            str_pad(dechex($G), 2, '0', STR_PAD_LEFT);
//    }
}
