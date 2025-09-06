<?php
namespace Magenest\Movie\Model\ResourceModel\Movie;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magenest\Movie\Model\Movie as MovieModel;
use Magenest\Movie\Model\ResourceModel\Movie as MovieResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'movie_id';
    protected $_eventPrefix = 'magenest_movie_collection';
    protected $_eventObject = 'movie_collection';

    protected function _construct()
    {
        $this->_init(MovieModel::class, MovieResource::class);
    }
}
