<?php
namespace Magenest\CustomUi\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\RequestInterface;

class AddDeliveryTimeToQuoteItem implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function execute(Observer $observer)
    {
        // Lấy dữ liệu POST trực tiếp
        $deliveryTimeType = $this->request->getPost('delivery_time_type');
        $deliveryDate     = $this->request->getPost('delivery_date');

        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        $quoteItem = $observer->getEvent()->getQuoteItem();

        if ($quoteItem) {
            if ($deliveryTimeType) {
                $quoteItem->setDeliveryTimeType($deliveryTimeType);
            }
            if ($deliveryDate) {
                $quoteItem->setDeliveryDate($deliveryDate);
            }
        }
    }
}

