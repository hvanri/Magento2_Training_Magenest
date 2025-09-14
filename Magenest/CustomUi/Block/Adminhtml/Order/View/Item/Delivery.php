<?php
namespace Magenest\CustomUi\Block\Adminhtml\Order\View\Item;

use Magento\Backend\Block\Template;

class Delivery extends Template
{
    protected $_item;

    public function setItem($item)
    {
        $this->_item = $item;
        return $this;
    }

    public function getItem()
    {
        return $this->_item;
    }
}
