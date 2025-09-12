<?php

namespace Magenest\Blog\Controller\View;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magenest\Blog\Model\BlogFactory;

class Index extends Action
{
    protected $blogFactory;

    public function __construct(
        Context $context,
        BlogFactory $blogFactory
    ) {
        parent::__construct($context);
        $this->blogFactory = $blogFactory;
    }

    public function execute()
    {
        $blogId = (int) $this->getRequest()->getParam('id');
        $blog = $this->blogFactory->create()->load($blogId);

        if (!$blog || !$blog->getId()) {
            /** Redirect to 404 page */
            return $this->resultFactory->create(ResultFactory::TYPE_FORWARD)
                ->forward('noroute');
        }

        /** Tạo result page */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        /** Thêm layout handle riêng nếu muốn */
        $resultPage->addHandle('blog_view_index'); // blog_view_index.xml trong layout

        /** Set title động */
        $resultPage->getConfig()->getTitle()->set($blog->getTitle());

        /** Truyền dữ liệu ra block nếu cần */
        $block = $resultPage->getLayout()->getBlock('blog.view');
        if ($block) {
            $block->setBlog($blog);
        }

        return $resultPage;
    }
}
