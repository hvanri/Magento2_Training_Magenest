<?php

namespace Magenest\Blog\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Cache\TypeListInterface;

class ClearBlogCache implements ObserverInterface
{
    protected $cacheTypeList;

    public function __construct(TypeListInterface $cacheTypeList)
    {
        $this->cacheTypeList = $cacheTypeList;
    }

    public function execute(Observer $observer)
    {
        /** @var \Vendor\Blog\Model\Blog $blog */
        $blog = $observer->getEvent()->getDataObject();

        if ($blog && $blog->getId()) {
            // Clear full page cache
            $this->cacheTypeList->cleanType('full_page');

        }

        return $this;
    }
}
