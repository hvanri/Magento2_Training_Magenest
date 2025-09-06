<?php
namespace Magenest\Movie\Block\Adminhtml\Movie\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveAndContinueButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Save and Continue Edit'),
            'class' => 'save',
            'data_attribute' => [
                'mage-init' => [
                    'button' => [
                        'event' => 'saveAndContinueEdit',
                        'target' => 'magenest_movie_form.magenest_movie_form',
                    ],
                ],
            ],
            'sort_order' => 95,
        ];
    }
}
