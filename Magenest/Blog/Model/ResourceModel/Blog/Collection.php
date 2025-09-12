<?php
namespace Magenest\Blog\Model\ResourceModel\Blog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magenest\Blog\Model\Blog as BlogModel;
use Magenest\Blog\Model\ResourceModel\Blog as BlogResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(BlogModel::class, BlogResource::class);
    }

    /**
     * Join để load thêm thông tin author
     */
    public function addAuthorData()
    {
        $this->getSelect()->joinLeft(
            ['author' => $this->getTable('admin_user')],
            'main_table.author_id = author.user_id',
            ['author_username' => 'author.username', 'author_email' => 'author.email']
        );

        return $this;
    }
}
