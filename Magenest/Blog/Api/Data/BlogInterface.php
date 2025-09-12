<?php

namespace Magenest\Blog\Api\Data;

interface BlogInterface
{
    /**
     * Get blog ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set blog ID
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get author ID (admin user ID)
     *
     * @return int|null
     */
    public function getAuthorId();

    /**
     * Set author ID
     *
     * @param int $authorId
     * @return $this
     */
    public function setAuthorId($authorId);

    /**
     * Get blog title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set blog title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get short description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set short description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get full blog content
     *
     * @return string|null
     */
    public function getContent();

    /**
     * Set full blog content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * Get URL rewrite (custom url key)
     *
     * @return string|null
     */
    public function getUrlRewrite();

    /**
     * Set URL rewrite
     *
     * @param string $urlRewrite
     * @return $this
     */
    public function setUrlRewrite($urlRewrite);

    /**
     * Get blog status
     *
     * @return int|null
     */
    public function getStatus();

    /**
     * Set blog status
     *
     * @param int $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get created at timestamp
     *
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created at timestamp
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated at timestamp
     *
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated at timestamp
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);
}
