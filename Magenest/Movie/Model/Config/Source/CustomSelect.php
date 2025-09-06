<?php
namespace Magenest\Movie\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class CustomSelect implements ArrayInterface
{
    /**
     * Options for custom select
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('show')],
            ['value' => 2, 'label' => __('not-show')]
        ];
    }

    /**
     * For compatibility
     *
     * @return array
     */
    public function toArray()
    {
        return [
            1 => __('show'),
            2 => __('not-show')
        ];
    }
}
