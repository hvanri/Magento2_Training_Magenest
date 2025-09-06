<?php
namespace Magenest\Movie\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class YesNoRed extends Field
{
    /**
     * Render field
     */
    public function render(AbstractElement $element)
    {
        $label = $element->getLabel(); // "Yes/No_Field_abcd"

        if (strpos($label, 'abcd') !== false) {
            $label = str_replace('abcd', '<span style="color:red;">abcd</span>', $label);
            $element->setData('label', $label);
        }

        return parent::render($element);
    }
}
