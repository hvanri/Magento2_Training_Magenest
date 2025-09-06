<?php
namespace Magenest\Movie\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Checkout\CustomerData\DefaultItem;
use Magento\Catalog\Helper\Image as ImageHelper;

class DefaultItemPlugin
{
    protected $productRepository;
    protected $imageHelper;

    public function __construct(ProductRepositoryInterface $productRepository, ImageHelper $imageHelper)
    {
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
    }

    /**
     * Thay đổi dữ liệu trả về cho mini-cart (name + ảnh)
     */
    public function afterGetItemData(DefaultItem $subject, array $result, QuoteItem $item): array
    {
        if ($item->getProductType() !== 'configurable') {
            return $result;
        }

        $option = $item->getOptionByCode('simple_product');
        if (!$option) {
            return $result;
        }

        $child = $option->getProduct();
        if (!$child) {
            try {
                $child = $this->productRepository->getById($option->getProductId());
            } catch (\Exception $e) {
                return $result;
            }
        }

        if ($child) {
            // Đổi tên
            $result['product_name'] = $child->getName();

            // Đổi ảnh (URL đầy đủ với ImageHelper)
            $imageUrl = $this->imageHelper
                ->init($child, 'product_thumbnail_image')
                ->getUrl();

            if (isset($result['product_image'])) {
                $result['product_image']['src'] = $imageUrl;
            } else {
                $result['product_image'] = ['src' => $imageUrl];
            }
        }

        return $result;
    }
}
