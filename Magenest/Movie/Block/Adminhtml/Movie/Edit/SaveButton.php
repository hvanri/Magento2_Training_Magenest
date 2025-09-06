<?php
namespace Magenest\Movie\Block\Adminhtml\Movie\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Save Movie'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'save',
                        'target' => 'magenest_movie_form.magenest_movie_form'
                    ],
                ],
            ],
            'sort_order' => 90,
        ];
    }
}
