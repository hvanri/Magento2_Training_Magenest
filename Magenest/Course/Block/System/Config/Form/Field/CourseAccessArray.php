<?php
namespace Magenest\Course\Block\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class CourseAccessArray extends AbstractFieldArray
{
    /**
     * @var \Magenest\Course\Block\System\Config\Form\Field\CustomerGroupColumn
     */
    protected $customerGroupRenderer;

    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'customer_group_id',
            [
                'label' => __('Customer Group'),
                'class' => 'required-entry',
                'renderer' => $this->getCustomerGroupRenderer()
            ]
        );

        $this->addColumn(
            'access_days',
            [
                'label' => __('Access Days'),
                'class' => 'required-entry validate-digits validate-zero-or-greater',
                'style' => 'width:100px'
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Access Rule');
    }

    /**
     * Prepare existing row data object
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $customerGroupId = $row->getCustomerGroupId();
        if ($customerGroupId !== null) {
            $options['option_' . $this->getCustomerGroupRenderer()->calcOptionHash($customerGroupId)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * Get customer group column renderer
     */
    protected function getCustomerGroupRenderer()
    {
        if (!$this->customerGroupRenderer) {
            $this->customerGroupRenderer = $this->getLayout()->createBlock(
                \Magenest\Course\Block\System\Config\Form\Field\CustomerGroupColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->customerGroupRenderer;
    }
    protected function _toHtml()
    {
        $html = parent::_toHtml();

        // Add custom JavaScript để handle delete buttons properly
        $html .= '
        <script type="text/javascript">
        require([
            "jquery"
        ], function($) {
            $(document).ready(function() {
                // Fix delete button event handlers
                $(document).on("click", ".action-delete", function(e) {
                    var $row = $(this).closest("tr");
                    var $table = $row.closest("table");

                    // Check minimum rows
//                    if ($table.find("tbody tr").length <= 1) {
//                        alert("' . __('At least one access rule is required.') . '");
//                        return false;
//                    }

                    $row.remove();
                    return false;
                });
            });
        });
        </script>';

        return $html;
    }
}
