<?php
namespace Magenest\CustomUi\Plugin\Checkout\CustomerData;

use Magento\Quote\Model\QuoteFactory;
use Magento\Checkout\Model\Session as CheckoutSession;

class Cart
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    public function __construct(
        CheckoutSession $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    public function afterGetSectionData(
        \Magento\Checkout\CustomerData\Cart $subject,
        array $result
    ) {
//        if (isset($result['items']) && is_array($result['items'])) {
//            foreach ($result['items'] as $key => $item) {
//                $quoteItem = $this->getQuoteItem($item['item_id']);
//                if ($quoteItem) {
//                    if ($quoteItem->getDeliveryTimeType()) {
//                        $result['items'][$key]['delivery_time_type'] = $quoteItem->getDeliveryTimeType();
//                    }
//                    if ($quoteItem->getDeliveryDate()) {
//                        $result['items'][$key]['delivery_date'] = $quoteItem->getDeliveryDate();
//                    }
//                }
//            }
//        }

        return $result;
    }

    protected function getQuoteItem($itemId)
    {
        $quote = $this->checkoutSession->getQuote();
        if (!$quote) {
            return null;
        }

        return $quote->getItemById($itemId);
    }
}
