<?php
namespace Magenest\Blog\Api;

use Magenest\Blog\Api\Data\BlogInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface BlogRepositoryInterface
{
    /**
     * Save Blog
     *
     * @param BlogInterface $blog
     * @return BlogInterface
     */
    public function save(BlogInterface $blog);

    /**
     * Get Blog by ID
     *
     * @param int $id
     * @return BlogInterface
     */
    public function getById($id);

    /**
     * Get list of blogs
     * @return mixed
     */
    public function getList();

    /**
     * Delete Blog
     *
     * @param BlogInterface $blog
     * @return bool true on success
     */
    public function delete(BlogInterface $blog);

    /**
     * Delete Blog by ID
     *
     * @param int $id
     * @return bool true on success
     */
    public function deleteById($id);
}
