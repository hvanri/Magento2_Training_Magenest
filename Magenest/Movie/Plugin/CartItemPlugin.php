<?php
namespace Magenest\Movie\Plugin;

use Magento\Checkout\CustomerData\Cart as Subject;
use Magento\Catalog\Model\Product\Type\Configurable as ConfigurableType;

class CartItemPlugin
{
    public function afterGetItemData(Subject $subject, $result, $item)
    {

        // check is configurable product
        $product = $item->getProduct();

        $writer = new \Monolog\Handler\StreamHandler(BP . '/var/log/plugin.log');
        $monolog = new \Monolog\Logger('plugin');
        $monolog->pushHandler($writer);
        $monolog->info(json_encode("Plugin in 1"));

        if ($product->getTypeId() == ConfigurableType::TYPE_CODE) {
            $childItems = $item->getChildren();
            if (isset($childItems[0])) {
                $child = $childItems[0]->getProduct();

                // replace children product's pictures
                $result['product_name'] = $child->getName();
                $result['product_price'] = $childItems[0]->getCalculationPrice();
                $result['product_url'] = $child->getProductUrl();
                $result['product_image']['url'] = $child->getThumbnailUrl();
            }
        }
        return $result;
    }
}
