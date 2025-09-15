<?php
namespace Magenest\CustomUi\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;

class Data extends AbstractHelper
{
    const CONFIG_PATH_COLOR_OPTIONS = 'magenest_customui/background_colors/color_options';

    /**
     * @var Json
     */
    protected $jsonSerializer;

    /**
     * @param Context $context
     * @param Json $jsonSerializer
     */
    public function __construct(
        Context $context,
        Json $jsonSerializer
    ) {
        parent::__construct($context);
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * Get color options from configuration
     *
     * @param int|null $storeId
     * @return array
     */
    public function getColorOptions($storeId = null)
    {
        $colorOptionsConfig = $this->scopeConfig->getValue(
            self::CONFIG_PATH_COLOR_OPTIONS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if ($colorOptionsConfig) {
            try {
                $colorOptions = $this->jsonSerializer->unserialize($colorOptionsConfig);
                return is_array($colorOptions) ? $colorOptions : [];
            } catch (\Exception $e) {
                $this->_logger->error('Error decoding color options: ' . $e->getMessage());
                return [];
            }
        }

        return [];
    }

    /**
     * Get color code by title
     *
     * @param string $title
     * @param int|null $storeId
     * @return string|null
     */
    public function getColorCodeByTitle($title, $storeId = null)
    {
        $colorOptions = $this->getColorOptions($storeId);

        foreach ($colorOptions as $option) {
            if (isset($option['color_title']) && $option['color_title'] === $title) {
                return isset($option['color_code']) ? $option['color_code'] : null;
            }
        }

        return null;
    }

    /**
     * Get formatted color options as array
     *
     * @param int|null $storeId
     * @return array
     */
    public function getFormattedColorOptions($storeId = null)
    {
        $colorOptions = $this->getColorOptions($storeId);
        $formatted = [];

        foreach ($colorOptions as $option) {
            if (isset($option['color_title']) && isset($option['color_code'])) {
                $formatted[$option['color_title']] = $option['color_code'];
            }
        }

        return $formatted;
    }

    /**
     * Generate CSS for color options
     *
     * @param int|null $storeId
     * @return string
     */
    public function generateColorCss($storeId = null)
    {
        $colorOptions = $this->getColorOptions($storeId);
        $css = '';

        foreach ($colorOptions as $option) {
            if (isset($option['color_title']) && isset($option['color_code'])) {
                $className = $this->sanitizeClassName($option['color_title']);
                $css .= ".bg-color-{$className} { background-color: {$option['color_code']} !important; }\n";
            }
        }

        return $css;
    }

    /**
     * Sanitize class name
     *
     * @param string $title
     * @return string
     */
    protected function sanitizeClassName($title)
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $title));
    }

    /**
     * Check if color options are enabled
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isColorOptionsEnabled($storeId = null)
    {
        $colorOptions = $this->getColorOptions($storeId);
        return !empty($colorOptions);
    }
}
