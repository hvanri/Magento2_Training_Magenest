<?php
namespace Magenest\Movie\Model\ResourceModel\MovieActor;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Magenest\Movie\Model\MovieActor::class,
            \Magenest\Movie\Model\ResourceModel\MovieActor::class
        );
    }
}
