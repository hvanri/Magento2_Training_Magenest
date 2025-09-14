<?php

namespace Magenest\CustomUi\Plugin;

use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;
use Magento\Quote\Model\Quote\Item\ToOrderItem;

class CopyDeliveryAttributes
{
    /**
     * Copy custom attributes from quote item to order item
     *
     * @param ToOrderItem $subject
     * @param OrderItem   $orderItem
     * @param QuoteItem   $quoteItem
     * @param array       $data
     * @return OrderItem
     */
    public function afterConvert(ToOrderItem $subject, OrderItem $orderItem, QuoteItem $quoteItem, $data = [])
    {
        if ($quoteItem->getDeliveryTimeType()) {
            $orderItem->setDeliveryTimeType($quoteItem->getDeliveryTimeType());
        }
        if ($quoteItem->getDeliveryDate()) {
            $orderItem->setDeliveryDate($quoteItem->getDeliveryDate());
        }

        return $orderItem;
    }
}
