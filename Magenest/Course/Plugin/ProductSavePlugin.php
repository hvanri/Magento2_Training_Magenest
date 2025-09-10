<?php
namespace Magenest\Course\Plugin;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magenest\Course\Model\CourseMaterialFactory;
use Magenest\Course\Model\ResourceModel\CourseMaterial as CourseMaterialResource;
use Psr\Log\LoggerInterface;

class ProductSavePlugin
{
    protected $request;
    protected $courseMaterialFactory;
    protected $courseMaterialResource;
    protected $logger;

    public function __construct(
        RequestInterface $request,
        CourseMaterialFactory $courseMaterialFactory,
        CourseMaterialResource $courseMaterialResource,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->courseMaterialFactory = $courseMaterialFactory;
        $this->courseMaterialResource = $courseMaterialResource;
        $this->logger = $logger;
    }

    /**
     * After save plugin for product
     *
     * @param ProductRepositoryInterface $subject
     * @param ProductInterface $result
     * @return ProductInterface
     */
    public function afterSave(ProductRepositoryInterface $subject, ProductInterface $result)
    {
        $this->logger->info("=== Product Save Plugin Triggered ===");

        $productId = (int)$result->getId();

        // Chỉ xử lý khi save từ admin form
        if (!$this->request->isPost()) {
            return $result;
        }

        $postData = $this->request->getParam('product');
        if (isset($postData['course_materials']) && is_array($postData['course_materials'])) {
            $this->logger->info("Found course materials", $postData['course_materials']);

            foreach ($postData['course_materials'] as $material) {
                try {
                    $model = $this->courseMaterialFactory->create();
                    $model->setData([
                        'product_id' => $productId,
                        'title'      => $material['title'] ?? '',
                        'file'       => $material['file'][0]['name'] ?? '', // hoặc 'url' tùy bạn muốn lưu gì
                    ]);

                    $this->courseMaterialResource->save($model);
                    $this->logger->info("Saved material for product_id {$productId}", $model->getData());
                } catch (\Exception $e) {
                    $this->logger->error("Error saving course material: " . $e->getMessage());
                }
            }
        } else {
            $this->logger->info("No course_materials found in request data.");
        }

        return $result;
    }
}
