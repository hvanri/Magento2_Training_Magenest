<?php
namespace Magenest\Movie\Model\ResourceModel\Actor\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    protected function _construct()
    {
        $this->_init(
            \Magenest\Movie\Model\Actor::class,
            \Magenest\Movie\Model\ResourceModel\Actor::class
        );
    }
}
