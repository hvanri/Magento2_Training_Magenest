<?php
namespace Magenest\Banner\Block;

use Magento\Framework\View\Element\Template;
use Magenest\Banner\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class Banner extends Template
{
    protected $collectionFactory;
    protected $position;
    protected $storeManager;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->position = $data['position'] ?? null;
    }

    public function getBanners()
    {
        if (!$this->position) {
            return [];
        }

        return $this->collectionFactory->create()
            ->addFieldToFilter('enabled', 1)
            ->addFieldToFilter('position', (int)$this->position)
            ->setOrder('sort_order', 'ASC');
    }

    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    public function getBannerImageUrl($banner)
    {
        if ($banner->getUploadImage()) {
            return $this->getMediaUrl() . ltrim($banner->getUploadImage(), '/');
        }
        return null;
    }
}
