<?php

namespace Magenest\Course\Model\Attribute\Backend;

use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;

class JsonBackend extends AbstractBackend
{
    public function beforeSave($object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        if (is_array($value)) {
        // Lưu JSON vào DB
            $object->setData($this->getAttribute()->getAttributeCode(), json_encode($value));
        }
        return parent::beforeSave($object);
    }

    public function afterLoad($object)
    {
        $value = $object->getData($this->getAttribute()->getAttributeCode());
        if ($value && is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $object->setData($this->getAttribute()->getAttributeCode(), $decoded);
            }
        }
        return parent::afterLoad($object);
    }
}
