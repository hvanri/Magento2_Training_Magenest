<?php
namespace Magenest\Movie\Model\ResourceModel\Movie\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    protected function _construct()
    {
        $this->_init(
            \Magenest\Movie\Model\Movie::class,
            \Magenest\Movie\Model\ResourceModel\Movie::class
        );
    }
}
