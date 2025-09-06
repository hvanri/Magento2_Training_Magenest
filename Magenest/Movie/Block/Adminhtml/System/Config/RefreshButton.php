<?php
namespace Magenest\Movie\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Backend\Block\Template;

class RefreshButton extends Template implements RendererInterface
{
    /**
     * Render button HTML
     */
    public function render(AbstractElement $element)
    {
        $html = '<button type="button" onclick="location.reload();" class="action-default scalable">'
            . 'Refresh Page'
            . '</button>';

        return $html;
    }
}
