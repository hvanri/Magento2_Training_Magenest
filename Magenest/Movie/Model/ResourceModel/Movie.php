<?php
namespace Magenest\Movie\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Movie extends AbstractDb
{
    protected function _construct()
    {
        // Table name: magenest_movie, Primary key: movie_id
        $this->_init('magenest_movie', 'movie_id');
    }
}
