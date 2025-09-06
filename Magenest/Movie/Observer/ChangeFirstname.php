<?php
namespace Magenest\Movie\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class ChangeFirstname implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $observer->getEvent()->getCustomer();

        //
        $newFirstname = 'Magenest';
        $customer->setFirstname($newFirstname);
    }
}
