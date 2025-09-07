<?php
namespace Magenest\Movie\Block\Adminhtml\Movie\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getButtonData()
    {

//        $backUrl = $this->context->getRequest()->getServer('HTTP_REFERER')
//            ?: $this->context->getUrl('*/*/');
//
//        return [
//            'label' => __('Back'),
//            'on_click' => sprintf(
//                "location.href = '%s';",
//                $backUrl
//            ),
//            'class' => 'back',
//            'sort_order' => 10
//        ];
    }
}
