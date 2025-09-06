<?php
namespace Magenest\Movie\Plugin;

use Magento\Catalog\Model\Product;

class CartItemRenderer
{
    /**
     * Plugin after getProduct
     * Trả về sản phẩm con nếu là configurable
     */
    public function afterGetProduct(\Magento\Checkout\Block\Cart\Item\Renderer $subject, $result)
    {
        $writer = new \Monolog\Handler\StreamHandler(BP . '/var/log/plugin.log');
        $monolog = new \Monolog\Logger('plugin');
        $monolog->pushHandler($writer);
        $monolog->info(json_encode("Plugin in 2"));

        $item = $subject->getItem();

        if ($item->getProductType() == 'configurable') {
            $child = $item->getOptionByCode('simple_product');
            if ($child) {
                return $child->getProduct();
            }
        }

        return $result;
    }

    /**
     * Plugin after getProductName
     * Trả về tên sản phẩm con
     */
    public function afterGetProductName(\Magento\Checkout\Block\Cart\Item\Renderer $subject, $result)
    {
        $item = $subject->getItem();
        if ($item->getProductType() == 'configurable') {
            $child = $item->getOptionByCode('simple_product');
            if ($child) {
                return $child->getProduct()->getName();
            }
        }
        return $result;
    }
}
