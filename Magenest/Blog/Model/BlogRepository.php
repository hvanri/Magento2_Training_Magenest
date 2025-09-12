<?php
namespace Magenest\Blog\Model;

use Magenest\Blog\Api\BlogRepositoryInterface;
use Magenest\Blog\Api\Data\BlogInterface;
use Magenest\Blog\Model\ResourceModel\Blog as BlogResource;
use Magenest\Blog\Model\ResourceModel\Blog\CollectionFactory as BlogCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;

class BlogRepository implements BlogRepositoryInterface
{
    protected $blogFactory;
    protected $blogResource;
    protected $collectionFactory;
    protected $searchResultsFactory;
    protected $collectionProcessor;
    public function __construct(
        \Magenest\Blog\Model\BlogFactory $blogFactory,
        BlogResource $blogResource,
        BlogCollectionFactory $collectionFactory,
        SearchResultsFactory $searchResultsFactory,
        \Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface $collectionProcessor
    ) {
        $this->blogFactory = $blogFactory;
        $this->blogResource = $blogResource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(BlogInterface $blog)
    {
        // ✅ Check duplicate url_rewrite
        if ($blog->getUrlRewrite()) {
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('url_rewrite', $blog->getUrlRewrite());

            if ($blog->getId()) {
                $collection->addFieldToFilter('id', ['neq' => $blog->getId()]);
            }

            if ($collection->getSize() > 0) {
                throw new LocalizedException(
                    __('The url_rewrite "%1" already exists. Please choose another one.', $blog->getUrlRewrite())
                );
            }
        }

        try {
            $this->blogResource->save($blog);
        } catch (\Exception $e) {
            throw new LocalizedException(__('Could not save blog: %1', $e->getMessage()));
        }

        return $blog;
    }

    public function getById($id)
    {
        $blog = $this->blogFactory->create();
        $this->blogResource->load($blog, $id);
        if (!$blog->getId()) {
            throw new NoSuchEntityException(__('Blog with id "%1" does not exist.', $id));
        }
        return $blog;
    }

    public function getList()
    {
        $collection = $this->collectionFactory->create();

        // join admin_user để lấy dữ liệu author
        $collection->getSelect()->joinLeft(
            ['au' => $collection->getTable('admin_user')],
            'main_table.author_id = au.user_id',
            ['author_name' => 'au.username', 'author_email' => 'au.email']
        );

        // bạn có thể áp dụng searchCriteria nếu muốn
        // $this->collectionProcessor->process($searchCriteria, $collection);

        $blogs = [];
        foreach ($collection as $blogModel) {
            $blogs[] = $blogModel->getData();
        }

        return [
            'items' => $blogs,
            'total_count' => $collection->getSize(),
        ];
    }

    public function delete(BlogInterface $blog)
    {
        try {
            $this->blogResource->delete($blog);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete Blog: %1', $e->getMessage()));
        }
        return true;
    }

    public function deleteById($id)
    {
        $blog = $this->getById($id);
        return $this->delete($blog);
    }
}
