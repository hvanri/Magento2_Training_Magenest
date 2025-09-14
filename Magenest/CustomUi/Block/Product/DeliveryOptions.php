<?php

namespace Magenest\CustomUi\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;

class DeliveryOptions extends Template
{
    protected $registry;

    public function __construct(
        Template\Context $context,
        Registry         $registry,
        array            $data = []
    )
    {
        $this->registry = $registry;
        parent::__construct($context, $data);
    }

    public function getCurrentProduct()
    {
        return $this->registry->registry('current_product');
    }

    public function getTomorrowDate()
    {
        return date('Y-m-d', strtotime('+1 day'));
    }

    public function getMaxDeliveryDate()
    {
        return date('Y-m-d', strtotime('+30 days'));
    }
}
