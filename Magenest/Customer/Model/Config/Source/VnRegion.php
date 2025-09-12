<?php
namespace Magenest\Customer\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class VnRegion extends AbstractSource
{
    /**
     * Get all options for vn_region select
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('Miền Bắc'), 'value' => 1],
                ['label' => __('Miền Trung'), 'value' => 2],
                ['label' => __('Miền Nam'), 'value' => 3],
            ];
        }
        return $this->_options;
    }
}
