<?php
namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Registry;
use Magenest\Movie\Model\MovieFactory;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $movieFactory;
    protected $coreRegistry;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        MovieFactory $movieFactory,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->movieFactory = $movieFactory;
        $this->coreRegistry = $coreRegistry;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('movie_id');
        $movie = $this->movieFactory->create();

        if ($id) {
            $movie->load($id);
            if (!$movie->getId()) {
                $this->messageManager->addErrorMessage(__('This movie no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        $this->coreRegistry->register('magenest_movie', $movie);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(
            $movie->getId() ? __("Edit Movie '%1'", $movie->getName()) : __('New Movie')
        );

        return $resultPage;
    }
}
