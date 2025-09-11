<?php
namespace Magenest\Course\Model\ResourceModel\CourseMaterial;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magenest\Course\Model\CourseMaterial as Model;
use Magenest\Course\Model\ResourceModel\CourseMaterial as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
