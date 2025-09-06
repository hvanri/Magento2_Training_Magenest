<?php
namespace Magenest\Movie\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class MovieActor extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('magenest_movie_actor', ['movie_id', 'actor_id']); // primary key nhiều cột
    }
}
