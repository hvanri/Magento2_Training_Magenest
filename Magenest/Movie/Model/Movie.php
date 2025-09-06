<?php
namespace Magenest\Movie\Model;

use Magento\Framework\Model\AbstractModel;

class Movie extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Magenest\Movie\Model\ResourceModel\Movie::class);
    }

    public function beforeSave()
    {
        parent::beforeSave();

        // Dispatch event tùy chỉnh trước khi save
        $this->_eventManager->dispatch(
            'magenest_movie_before_save',
            ['movie' => $this]
        );

        return $this;
    }
}
