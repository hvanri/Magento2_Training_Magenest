<?php
namespace Magenest\Course\Observer;
use Magenest\Course\Ui\DataProvider\Product\Form\Modifier\DynamicRowAttribute;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
class SaveDynamicRowValues implements ObserverInterface
{
    /**
     * Dependency Initilization
     *
     * @param RequestInterface $request
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     */
    public function __construct(
        protected RequestInterface $request,
        protected \Magento\Framework\Serialize\SerializerInterface $serializer,
    ) {
    }
    /**
     * Execute
     *
     * @param Observer $observer
     * @return this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getDataObject();
        $wholeRequest = $this->request->getPost();
        $post = $wholeRequest['product'] ?? [];

        // Fallback nếu không có post chính
        if (empty($post) && !empty($wholeRequest['variables']['product'])) {
            $post = $wholeRequest['variables']['product'];
        }

        // Danh sách các attribute cần xử lý
        $attributes = [
            //DynamicRowAttribute::PRODUCT_ATTRIBUTE_OLD_CODE,
            DynamicRowAttribute::PRODUCT_ATTRIBUTE_FILE_CODE,
            DynamicRowAttribute::PRODUCT_ATTRIBUTE_TEXT_CODE
        ];

        foreach ($attributes as $attributeCode) {
            $dynamicData = $post[$attributeCode] ?? null;

            if (is_array($dynamicData)) {
                // Xóa các record rỗng theo các trường bắt buộc
                $requiredParams = ['title', 'file'];
                if($attributeCode == DynamicRowAttribute::PRODUCT_ATTRIBUTE_TEXT_CODE) {
                    $requiredParams = ['title', 'content'];
                }
                $cleanData = $this->removeEmptyArray($dynamicData, $requiredParams, $attributeCode);

                if (!empty($cleanData)) {
                    // Lưu dưới dạng JSON (serialize)
                    $product->setData($attributeCode, $this->serializer->serialize($cleanData));
                } else {
                    $product->setData($attributeCode, null);
                }
            }
        }
    }

    /**
     * Loại bỏ các record rỗng trong array
     *
     * @param array $data
     * @param array $requiredParams
     * @return array
     */
    private function removeEmptyArray(array $data, array $requiredParams, $attributeCode): array
    {
        $requiredParams = array_combine($requiredParams, $requiredParams);
        $reqCount = count($requiredParams);

        foreach ($data[$attributeCode] as $key => $values) {
            $valid = false;
            $intersectCount = count(array_intersect_key(array_filter($values), $requiredParams));
            if ($intersectCount === $reqCount) {
                $valid = true;
            }
            if (!$valid) {
                unset($data[$attributeCode][$key]);
            }
        }

        return $data;
    }
}
