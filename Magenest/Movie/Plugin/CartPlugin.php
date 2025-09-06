<?php
namespace Magenest\Movie\Plugin;

use Magento\Quote\Model\Quote\Item as QuoteItem;

class CartPlugin
{
    /**
     * After addProduct plugin
     *
     * @param \Magento\Checkout\Model\Cart $subject
     * @param \Magento\Checkout\Model\Cart $result
     * @param \Magento\Catalog\Model\Product|int $productInfo
     * @param array|\Magento\Framework\DataObject|null $requestInfo
     * @return \Magento\Checkout\Model\Cart
     */
    public function afterAddProduct(
        \Magento\Checkout\Model\Cart $subject,
                                     $result,
                                     $productInfo,
                                     $requestInfo = null
    ) {
        $writer = new \Monolog\Handler\StreamHandler(BP . '/var/log/plugin.log');
        $monolog = new \Monolog\Logger('plugin');
        $monolog->pushHandler($writer);
        $monolog->info(json_encode("Plugin in 3"));

        foreach ($subject->getQuote()->getAllItems() as $item) {
            $monolog->info(json_encode("step 1"));
            // Kiểm tra nếu là configurable
            if ($item->getProductType() === 'configurable') {
                $monolog->info(json_encode("configurable"));
                $children = $item->getChildren();
                $monolog->info(json_encode($children));
                if (!empty($children)) {
                    $child = $children[0]; // lấy sản phẩm con đầu tiên
                    // Set tên và product cho quote item
                    //$item->setName($child->getName());
                    $monolog->info($child->getName());
                    $item = null;
                    // Optional: set hình ảnh nếu cần
                    //$item->setData('thumbnail', $child->getThumbnail());
                    $monolog->info($child->getName());
                }
            }
        }
        return $result;
    }
}
