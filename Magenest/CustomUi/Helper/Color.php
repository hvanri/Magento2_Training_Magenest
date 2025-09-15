<?php
namespace Magenest\CustomUi\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Color extends AbstractHelper
{
    const XML_PATH_COLOR_OPTIONS = 'magenest_customui/background_colors/color_options';

    /**
     * Lấy tất cả color options từ backend
     *
     * @return array
     */
    public function getColorOptions(): array
    {
        $json = $this->scopeConfig->getValue(
            self::XML_PATH_COLOR_OPTIONS,
            ScopeInterface::SCOPE_STORE
        );

        $options = json_decode($json, true);
        $result = [];

        if (!empty($options) && is_array($options)) {
            foreach ($options as $row) {
                $id = key($row);
                $result[] = [
                    'title' => $row[$id]['color_title'] ?? 'Default',
                    'code' => $row[$id][$id] ?? '#ffffff'
                ];
            }
        }

        // Thêm default nếu muốn
        array_unshift($result, [
            'title' => 'Default',
            'code' => '#ffffff'
        ]);

        return $result;
    }

    /**
     * Lấy default color (màu đầu tiên hoặc màu mặc định)
     *
     * @return string
     */
    public function getDefaultColor(): string
    {
        $colors = $this->getColorOptions();
        return $colors[0]['code'] ?? '#ffffff';
    }
}
