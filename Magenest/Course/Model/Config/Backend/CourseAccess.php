<?php
namespace Magenest\Course\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Exception\ValidatorException;

class CourseAccess extends Value
{
    /**
     * Process data after load
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();
        if ($value) {
            $decodedValue = json_decode($value, true);
            if (is_array($decodedValue)) {
                $this->setValue($decodedValue);
            }
        }
        return parent::_afterLoad();
    }

    /**
     * Prepare data before save
     */
    public function beforeSave()
    {
        $value = $this->getValue();

        if (is_array($value)) {
            // Remove empty rows
            $value = array_filter($value, function($row) {
                return !empty($row['customer_group_id']) && isset($row['access_days']);
            });

            // Validate data
            $this->validateRules($value);

            // Re-index array and encode
            $value = array_values($value);
            $this->setValue(json_encode($value));
        }

        return parent::beforeSave();
    }

    /**
     * Validate access rules
     */
    protected function validateRules($rules)
    {
        $usedGroups = [];

        foreach ($rules as $rule) {
            // Check required fields
            if (empty($rule['customer_group_id']) || !isset($rule['access_days'])) {
                throw new ValidatorException(__('All fields are required for course access rules.'));
            }

            // Check for duplicates
            if (in_array($rule['customer_group_id'], $usedGroups)) {
                throw new ValidatorException(__('Duplicate customer group found in access rules.'));
            }
            $usedGroups[] = $rule['customer_group_id'];

            // Validate access days
            if (!is_numeric($rule['access_days']) || $rule['access_days'] < 0) {
                throw new ValidatorException(__('Access days must be a non-negative number.'));
            }
        }
    }
}
