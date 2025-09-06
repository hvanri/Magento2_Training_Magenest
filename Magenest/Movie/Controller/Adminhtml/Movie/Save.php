<?php
namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magenest\Movie\Model\MovieFactory;
use Magento\Framework\Exception\LocalizedException;


class Save extends Action
{
    protected $movieFactory;

    public function __construct(
        Context $context,
        MovieFactory $movieFactory
    ) {
        parent::__construct($context);
        $this->movieFactory = $movieFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            $this->messageManager->addErrorMessage(__('No data to save.'));
            return $this->_redirect('*/*/');
        }

        // Log dữ liệu
        $writer = new \Monolog\Handler\StreamHandler(BP . '/var/log/movie_save.log');
        $monolog = new \Monolog\Logger('movie_save');
        $monolog->pushHandler($writer);
        $monolog->info(json_encode($data));

        try {
            $id = $this->getRequest()->getParam('movie_id');
            $model = $this->movieFactory->create();

            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    throw new LocalizedException(__('This movie no longer exists.'));
                }
            } else {
                unset($data['movie_id']);
            }

            $model->setData($data);
            $model->save();

            $this->messageManager->addSuccessMessage(__('Movie saved successfully.'));

            if ($this->getRequest()->getParam('back')) {
                return $this->_redirect('*/*/edit', ['movie_id' => $model->getId()]);
            }
            return $this->_redirect('*/*/');

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the movie.'));
        }

        return $this->_redirect('*/*/edit', ['movie_id' => $this->getRequest()->getParam('movie_id')]);
    }
}
