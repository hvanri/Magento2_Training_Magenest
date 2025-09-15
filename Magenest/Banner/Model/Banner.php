<?php
namespace Magenest\Banner\Model;

use Magento\Framework\Model\AbstractModel;

class Banner extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(\Magenest\Banner\Model\ResourceModel\Banner::class);
    }
}
