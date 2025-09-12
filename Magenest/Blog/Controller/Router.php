<?php
namespace Magenest\Blog\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\RequestInterface;
use Magenest\Blog\Model\ResourceModel\Blog\CollectionFactory;

class Router implements RouterInterface
{
    protected $actionFactory;
    protected $collectionFactory;

    public function __construct(
        ActionFactory $actionFactory,
        CollectionFactory $collectionFactory
    ) {
        $this->actionFactory = $actionFactory;
        $this->collectionFactory = $collectionFactory;
    }

    public function match(RequestInterface $request)
    {
        $path = trim($request->getPathInfo(), '/'); // ví dụ: blog/view/id/1 hoặc blog/my-first-post

        if (!$path || strpos($path, 'blog/') !== 0) {
            return null; // không thuộc route này
        }

        // Lấy phần sau "blog/"
        $subPath = substr($path, strlen('blog/'));

        $blog = null;

        // --- Trường hợp /blog/view/id/1 ---
        if (preg_match('#^view/id/(\d+)$#', $subPath, $matches)) {
            $id = (int)$matches[1];
            if ($id > 0) {
                $blog = $this->collectionFactory->create()
                    ->addFieldToFilter('id', $id)
                    ->getFirstItem();
            }
        } else {
            // --- Trường hợp /blog/{url_rewrite} ---
            $identifier = $subPath;
            $blog = $this->collectionFactory->create()
                ->addFieldToFilter('url_rewrite', $identifier)
                ->getFirstItem();
        }

        if ($blog && $blog->getId()) {
            $request->setModuleName('blog')
                ->setControllerName('view')
                ->setActionName('index')
                ->setParam('id', $blog->getId())
                ->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $path);

            return $this->actionFactory->create(
                \Magento\Framework\App\Action\Forward::class
            );
        }

        return null;
    }
}
