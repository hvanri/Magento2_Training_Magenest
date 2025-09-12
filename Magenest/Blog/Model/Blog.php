<?php

namespace Magenest\Blog\Model;

use Magenest\Blog\Api\Data\BlogInterface;
use Magento\Framework\Model\AbstractModel;

class Blog extends AbstractModel implements BlogInterface
{
    /**
     * Cache tag
     */
    protected $_cacheTag = 'magenest_blog';

    /**
     * Event prefix
     */
    protected $_eventPrefix = 'magenest_blog';

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(\Magenest\Blog\Model\ResourceModel\Blog::class);
        $this->setIdFieldName('id');
    }
    protected function _afterSave()
    {
        parent::_afterSave();

        // Trigger custom event sau khi blog save
        $this->_eventManager->dispatch('magenest_blog_save_after', ['blog' => $this]);
    }

    /** @inheritdoc */
    public function getId()
    {
        return $this->getData('id');
    }

    /** @inheritdoc */
    public function setId($id)
    {
        return $this->setData('id', $id);
    }

    /** @inheritdoc */
    public function getAuthorId()
    {
        return $this->getData('author_id');
    }

    /** @inheritdoc */
    public function setAuthorId($authorId)
    {
        return $this->setData('author_id', $authorId);
    }

    /** @inheritdoc */
    public function getTitle()
    {
        return $this->getData('title');
    }

    /** @inheritdoc */
    public function setTitle($title)
    {
        return $this->setData('title', $title);
    }

    /** @inheritdoc */
    public function getDescription()
    {
        return $this->getData('description');
    }

    /** @inheritdoc */
    public function setDescription($description)
    {
        return $this->setData('description', $description);
    }

    /** @inheritdoc */
    public function getContent()
    {
        return $this->getData('content');
    }

    /** @inheritdoc */
    public function setContent($content)
    {
        return $this->setData('content', $content);
    }

    /** @inheritdoc */
    public function getUrlRewrite()
    {
        return $this->getData('url_rewrite');
    }

    /** @inheritdoc */
    public function setUrlRewrite($urlRewrite)
    {
        return $this->setData('url_rewrite', $urlRewrite);
    }

    /** @inheritdoc */
    public function getStatus()
    {
        return $this->getData('status');
    }

    /** @inheritdoc */
    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }

    /** @inheritdoc */
    public function getCreatedAt()
    {
        return $this->getData('create_at');
    }

    /** @inheritdoc */
    public function setCreatedAt($createdAt)
    {
        return $this->setData('create_at', $createdAt);
    }

    /** @inheritdoc */
    public function getUpdatedAt()
    {
        return $this->getData('update_at');
    }

    /** @inheritdoc */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData('update_at', $updatedAt);
    }
}
