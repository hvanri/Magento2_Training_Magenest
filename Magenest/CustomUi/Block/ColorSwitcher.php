<?php
namespace Magenest\CustomUi\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ColorSwitcher extends Template
{
    const XML_PATH_COLOR_OPTIONS = 'magenest_customui/background_colors/color_options';

    protected $scopeConfig;

    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * Get color options from configuration
     */
    public function getColorOptions()
    {
        // Thay đổi path này theo config path của bạn
        $colorConfig = $this->scopeConfig->getValue(
            self::XML_PATH_COLOR_OPTIONS,
            ScopeInterface::SCOPE_STORE
        );

        if ($colorConfig) {
            $colors = json_decode($colorConfig, true);
            return is_array($colors) ? $colors : [];
        }

        return [];
    }

    /**
     * Get current active color
     */
    public function getCurrentColor()
    {
        $colors = $this->getColorOptions();
        return !empty($colors) ? $colors[0] : ['color_title' => 'Default', 'color_code' => '#ffffff'];
    }

    public function lightenColor($color, $percent) {
        $color = ltrim($color, '#');
        $num = hexdec($color);
        $amt = round(2.55 * $percent);

        $R = ($num >> 16) + $amt;
        $B = ($num >> 8 & 0x00FF) + $amt;
        $G = ($num & 0x0000FF) + $amt;

        $R = ($R < 255) ? ($R < 1 ? 0 : $R) : 255;
        $B = ($B < 255) ? ($B < 1 ? 0 : $B) : 255;
        $G = ($G < 255) ? ($G < 1 ? 0 : $G) : 255;

        return '#' . str_pad(dechex($R), 2, '0', STR_PAD_LEFT) .
            str_pad(dechex($B), 2, '0', STR_PAD_LEFT) .
            str_pad(dechex($G), 2, '0', STR_PAD_LEFT);
    }
}
