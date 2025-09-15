<?php
namespace Magenest\Banner\Model\ResourceModel\Banner;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            \Magenest\Banner\Model\Banner::class,
            \Magenest\Banner\Model\ResourceModel\Banner::class
        );
    }
}
