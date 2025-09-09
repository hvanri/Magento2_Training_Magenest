<?php
namespace Magenest\Movie\Block\Adminhtml\Sale;

use Magento\Backend\Block\Widget\Button;
use Magento\Backend\Block\Widget\Context;

class CustomButton extends \Magento\Backend\Block\Widget\Button
{
    protected $_context;

    public function __construct(Context $context, array $data = [])
    {
        $this->_context = $context;
        parent::__construct($context, $data);
    }

    public function getButtonData()
    {
        return [
            'label' => __('Export Items CSV'),
            'onclick' => sprintf("location.href = '%s';", $this->_context->getUrl('movie/order/exportCsv')),
            'class' => 'primary',
            'sort_order' => 10
        ];
    }
}
