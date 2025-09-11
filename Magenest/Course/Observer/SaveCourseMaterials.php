<?php
namespace Magenest\Course\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\RequestInterface;
use Magenest\Course\Model\CourseMaterialFactory;
use Magenest\Course\Model\ResourceModel\CourseMaterial as CourseMaterialResource;

class SaveCourseMaterials implements ObserverInterface
{
    protected $request;
    protected $courseMaterialFactory;
    protected $courseMaterialResource;

    public function __construct(
        RequestInterface $request,
        CourseMaterialFactory $courseMaterialFactory,
        CourseMaterialResource $courseMaterialResource
    ) {
        $this->request = $request;
        $this->courseMaterialFactory = $courseMaterialFactory;
        $this->courseMaterialResource = $courseMaterialResource;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        $productId = $product->getId();

        $postData = $this->request->getParam('product');
        if (isset($postData['course_materials']) && is_array($postData['course_materials'])) {


            foreach ($postData['course_materials'] as $material) {
                $model = $this->courseMaterialFactory->create();
                $model->setData([
                    'product_id' => $productId,
                    'title'      => $material['title'] ?? '',
                    'file'       => $material['file'][0]['url'] ?? '',
                ]);
                $this->courseMaterialResource->save($model);

            }
        } else {

        }
    }
}
