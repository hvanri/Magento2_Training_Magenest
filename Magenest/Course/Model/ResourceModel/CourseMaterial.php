<?php
namespace Magenest\Course\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CourseMaterial extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('course_materials', 'material_id');
    }
}
