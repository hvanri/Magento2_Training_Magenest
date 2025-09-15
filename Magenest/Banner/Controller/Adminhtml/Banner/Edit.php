<?php
namespace Magenest\Banner\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Magenest\Banner\Model\BannerFactory;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $bannerFactory;
    protected $coreRegistry;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        BannerFactory $bannerFactory,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->bannerFactory = $bannerFactory;
        $this->coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('banner_id');
        $banner = $this->bannerFactory->create();

        if ($id) {
            $banner->load($id);
            if (!$banner->getBannerId()) {
                $this->messageManager->addErrorMessage(__('This banner no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        $this->coreRegistry->register('magenest_banner', $banner);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(
            $banner->getBannerId() ? __("Edit Banner '%1'", $banner->getName()) : __('New Banner')
        );

        return $resultPage;
    }
}
