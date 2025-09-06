<?php
namespace Magenest\Movie\Model\ResourceModel\Director;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Magenest\Movie\Model\Director::class,
            \Magenest\Movie\Model\ResourceModel\Director::class
        );
    }
}
