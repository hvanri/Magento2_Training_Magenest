<?php
namespace Magenest\Movie\Controller\Adminhtml\Avatar;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magenest\Movie\Model\ImageUploader;

class Upload extends \Magento\Backend\App\Action
{
    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    public function execute()
    {
        $imageId = $this->getRequest()->getParam('param_name', 'avatar');

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultFactory
            ->create(ResultFactory::TYPE_JSON)
            ->setData($result);
    }
}
