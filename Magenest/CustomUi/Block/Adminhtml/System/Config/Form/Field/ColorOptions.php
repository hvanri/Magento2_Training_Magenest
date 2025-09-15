<?php
namespace Magenest\CustomUi\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

class ColorOptions extends AbstractFieldArray
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        array $data = []
    ) {
        $this->_elementFactory = $elementFactory;
        parent::__construct($context, $data);
    }

    /**
     * Initialise columns for 'Color Options'
     *
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'color_title',
            [
                'label' => __('Color Title'),
                'class' => 'required-entry validate-length maximum-length-255',
                'style' => 'width:200px'
            ]
        );

        $this->addColumn(
            'color_code',
            [
                'label' => __('Color Code'),
                'class' => 'required-entry validate-color',
                'style' => 'width:300px'
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Color Option');
    }

    /**
     * Render array cell
     *
     * @param string $columnName
     * @param string $html
     * @param DataObject $row
     * @return string
     */
    public function renderCellTemplate($columnName, $html = null, DataObject $row = null)
    {
        if ($columnName !== 'color_code') {
            return parent::renderCellTemplate($columnName, $html, $row);
        }

        // Knockout template value
        $colorValue = '<%- (typeof color_code !== \'undefined\' && color_code ? color_code : \'#000000\') %>';

        // Name của input
        $name = sprintf(
            'groups[background_colors][fields][color_options][value][%s][color_code]',
            '<%- _id %>'
        );

        // HTML template
        $template = <<<HTML
<div class="color-picker-wrapper">
    <input type="color"
           id="{$this->_getCellInputElementId('<%- _id %>', $columnName)}"
           name="{$name}"
           value="{$colorValue}"
           class="color-picker-input"
           data-column="color_code"
           data-debug-value="{$colorValue}" />
    <div class="color-display"
         style="background-color: {$colorValue};"
         data-original-color="{$colorValue}">
         {$colorValue}
    </div>
</div>
HTML;

        return $template;
    }

    /**
     * Get the grid and scripts contents
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = parent::_getElementHtml($element);

        // Add custom CSS and JavaScript for color picker
        $html .= '<style>
            .color-picker-wrapper {
                position: relative;
                display: inline-block;
                width: 280px;
            }

            .color-picker-input {
                position: absolute;
                opacity: 0;
                width: 100%;
                height: 40px;
                cursor: pointer;
                z-index: 2;
            }

            .color-display {
                width: 100%;
                height: 40px;
                border: 2px solid #ccc;
                border-radius: 5px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                z-index: 1;
            }

            .color-display:hover {
                border-color: #007cba;
                box-shadow: 0 0 5px rgba(0, 124, 186, 0.3);
                transform: scale(1.02);
            }

            .admin__field-control .color-picker-wrapper {
                margin: 0;
            }

            /* Dark text for light colors */
            .color-display.light-color {
                color: #333 !important;
                text-shadow: 1px 1px 2px rgba(255,255,255,0.7);
            }
        </style>';

        $html .= '<script type="text/javascript">
            require(["jquery", "domReady!"], function($) {
                // Function to determine if color is light or dark
                function isLightColor(hex) {
                    const r = parseInt(hex.substr(1, 2), 16);
                    const g = parseInt(hex.substr(3, 2), 16);
                    const b = parseInt(hex.substr(5, 2), 16);
                    const brightness = (r * 299 + g * 587 + b * 114) / 1000;
                    return brightness > 128;
                }

                // Update color display
                function updateColorDisplay($input) {
                    const colorValue = $input.val();
                    const $display = $input.siblings(".color-display");

                    $display.css("background-color", colorValue);
                    $display.text(colorValue);

                    // Adjust text color based on background
                    if (isLightColor(colorValue)) {
                        $display.addClass("light-color");
                    } else {
                        $display.removeClass("light-color");
                    }
                }

                // Initialize color pickers with saved values
                function initializeColorPickers() {
                    $("input[type=color][data-column=color_code]").each(function() {
                        var $colorInput = $(this);
                        var savedValue = $colorInput.val();

                        // Ensure we have a valid hex color
                        if (savedValue && savedValue.match(/^#[0-9A-F]{6}$/i)) {
                            $colorInput.val(savedValue);
                        } else if (savedValue && !savedValue.startsWith("#")) {
                            // Add # if missing
                            var colorWithHash = "#" + savedValue;
                            if (colorWithHash.match(/^#[0-9A-F]{6}$/i)) {
                                $colorInput.val(colorWithHash);
                                savedValue = colorWithHash;
                            }
                        }

                        // Update the display
                        updateColorDisplay($colorInput);
                    });
                }

                // Initialize on page load
                setTimeout(initializeColorPickers, 500);

                // Handle color picker changes
                $(document).on("change", "input[type=color][data-column=color_code]", function() {
                    updateColorDisplay($(this));
                });

                // Handle clicks on color display (to trigger color picker)
                $(document).on("click", ".color-display", function() {
                    $(this).siblings("input[type=color]").click();
                });

                // Re-initialize when new rows are added
                $(document).on("click", "button[id$=_add]", function() {
                    setTimeout(function() {
                        initializeColorPickers();
                    }, 200);
                });

                // Initialize when existing data is loaded
                $(document).ajaxComplete(function() {
                    setTimeout(function() {
                        initializeColorPickers();
                    }, 200);
                });

                // Handle form submission to ensure values are properly set
                $(document).on("submit", "form", function() {
                    $("input[type=color][data-column=color_code]").each(function() {
                        var colorValue = $(this).val();
                        if (colorValue) {
                            $(this).attr("value", colorValue);
                        }
                    });
                });
            });
        </script>';

        return $html;
    }

    /**
     * Prepare existing row data object
     *
     * @param DataObject $row
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];
        $row->setData('option_extra_attrs', $options);
    }


}
