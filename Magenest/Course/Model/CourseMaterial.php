<?php
namespace Magenest\Course\Model;

use Magento\Framework\Model\AbstractModel;

class CourseMaterial extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Magenest\Course\Model\ResourceModel\CourseMaterial::class);
    }
}
