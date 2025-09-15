<?php
namespace Magenest\Banner\Model\ResourceModel\Banner\Grid;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    protected function _construct()
    {
        $this->_init(
            \Magenest\Banner\Model\Banner::class,
            \Magenest\Banner\Model\ResourceModel\Banner::class
        );
    }
}
