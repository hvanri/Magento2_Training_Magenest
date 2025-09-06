<?php
namespace Magenest\Movie\Model\ResourceModel\Director\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    protected function _construct()
    {
        $this->_init(
            \Magenest\Movie\Model\Director::class,
            \Magenest\Movie\Model\ResourceModel\Director::class
        );
    }
}
