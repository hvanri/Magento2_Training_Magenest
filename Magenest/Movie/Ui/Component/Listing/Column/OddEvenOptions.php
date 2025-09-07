<?php
namespace Magenest\Movie\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;

class OddEvenOptions implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('Odd')],
            ['value' => 2, 'label' => __('Even')],
        ];
    }
}
