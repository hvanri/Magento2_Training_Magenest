<?php
namespace Magenest\CustomUi\Controller\Color;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Options implements HttpGetActionInterface
{
    private $jsonFactory;
    private $scopeConfig;

    const XML_PATH_COLOR_OPTIONS = 'magenest_customui/background_colors/color_options';

    public function __construct(
        JsonFactory $jsonFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $result = $this->jsonFactory->create();

        try {
            // Get colors from config
            $colorConfig = $this->scopeConfig->getValue(
                self::XML_PATH_COLOR_OPTIONS,
                ScopeInterface::SCOPE_STORE
            );

            $colors = [];
            if ($colorConfig) {
                $configColors = json_decode($colorConfig, true);
                if (is_array($configColors)) {
                    $colors = $configColors;
                }
            }

            return $result->setData([
                'success' => true,
                'colors' => $colors,
                'message' => 'Colors loaded successfully'
            ]);
        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'colors' => [],
                'message' => $e->getMessage()
            ]);
        }
    }
}
