<?php

namespace Magenest\Movie\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magenest\Movie\Model\ImageUploader;

class SaveCustomerAvatar implements ObserverInterface
{
    protected $imageUploader;

    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function execute(Observer $observer)
    {
//        /** @var \Magento\Customer\Model\Customer $customer */
//        $customer = $observer->getCustomer();
//
//        $avatar = $customer->getData('avatar'); // phải là 'ex7_2_15.png', không có URL
//
//        if ($avatar) {
//            // Move từ tmp → folder chính
//            $fileName = $this->imageUploader->moveFileFromTmp($avatar);
//            var_dump();
//            // Set lại attribute avatar
//            $customer->setCustomAttribute('avatar', $fileName);
//        }
    }
}
