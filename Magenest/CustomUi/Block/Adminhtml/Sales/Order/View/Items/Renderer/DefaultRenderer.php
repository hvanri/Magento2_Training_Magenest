<?php
namespace Magenest\CustomUi\Block\Adminhtml\Sales\Order\View\Items\Renderer;

use Magento\Framework\DataObject;
use Magento\Sales\Block\Adminhtml\Order\View\Items\Renderer\DefaultRenderer as MagentoDefaultRenderer;

class DefaultRenderer extends MagentoDefaultRenderer
{
    public function getColumnHtml(DataObject $item, $column, $field = null)
    {
        $html = '';
        $custom_column = "Custom Column Value";

        switch ($column) {
            case 'product':
                if ($this->canDisplayContainer()) {
                    $html .= '<div id="' . $this->getHtmlId() . '">';
                }
                $html .= $this->getColumnHtml($item, 'name');
                if ($this->canDisplayContainer()) {
                    $html .= '</div>';
                }
                break;

            case 'status':
                $html = $item->getStatus();
                break;

            case 'price-original':
                $html = $this->displayPriceAttribute('original_price');
                break;

            case 'discount_percent':
                $html = $custom_column;
                break;

            case 'discount':
                $html = $this->displayPriceAttribute('discount_amount');
                break;

            // ✅ Custom column hiển thị Delivery Type
            case 'delivery_time_type':
                $html = $item->getData('delivery_time_type') ?: __('N/A');
                break;

            // ✅ Custom column hiển thị Delivery Date
            case 'delivery_date':
                $html = $item->getData('delivery_date')
                    ? date('d/m/Y H:i', strtotime($item->getData('delivery_date')))
                    : __('N/A');
                break;

            default:
                $html = parent::getColumnHtml($item, $column, $field);
        }

        return $html;
    }
}
