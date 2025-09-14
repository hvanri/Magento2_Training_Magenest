<?php

namespace Magenest\CustomUi\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddToCartBefore implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $request = $observer->getRequest();
        $item = $observer->getQuoteItem();

        $deliveryTimeType = $request->getParam('delivery_time_type');
        $deliveryDate = $request->getParam('delivery_date');

        if ($deliveryTimeType) {
            $item->setDeliveryTimeType($deliveryTimeType);
        }

        if ($deliveryDate) {
            $item->setDeliveryDate($deliveryDate);
        }

        return $this;
    }
}
