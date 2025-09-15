<?php
namespace Magenest\Banner\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magenest\Banner\Model\BannerFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;


class Save extends Action
{
    protected $bannerFactory;
    protected $filesystem;

    public function __construct(
        Context $context,
        BannerFactory $bannerFactory,
        Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;
        $this->bannerFactory = $bannerFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        if (!$data) {
            $this->messageManager->addErrorMessage(__('No data to save.'));
            return $this->_redirect('*/*/');
        }

        // Log dữ liệu
        $writer = new \Monolog\Handler\StreamHandler(BP . '/var/log/banner_save.log');
        $monolog = new \Monolog\Logger('banner_save');
        $monolog->pushHandler($writer);
        $monolog->info(json_encode($data));

        try {
            $id = $this->getRequest()->getParam('banner_id');
            $model = $this->bannerFactory->create();

            if ($id) {
                $model->load($id);
                if (!$model->getId()) {
                    throw new LocalizedException(__('This banner no longer exists.'));
                }
            } else {
                unset($data['banner_id']);
            }
            //
            if (isset($data['upload_image']) && is_array($data['upload_image'])) {
                $image = $data['upload_image'][0]['name'] ?? null;

                if ($image) {
                    // Đường dẫn tmp file
                    $tmpFile = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)
                        ->getAbsolutePath('tmp/banner/' . $image);

                    // Đường dẫn save chính thức
                    $destPath = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA)
                        ->getAbsolutePath('banner/' . $image);

                    try {
                        $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA)->renameFile(
                            'tmp/banner/' . $image,
                            'banner/' . $image
                        );
                    } catch (\Exception $e) {
                        $this->messageManager->addErrorMessage(__('Cannot move uploaded file: %1', $e->getMessage()));
                    }

                    // Lưu tên file vào DB
                    $data['upload_image'] = 'banner/' . $image;
                }
            }

            $model->setData($data);
            $model->save();

            $this->messageManager->addSuccessMessage(__('Banner saved successfully.'));

            if ($this->getRequest()->getParam('back')) {
                return $this->_redirect('*/*/edit', ['banner_id' => $model->getId()]);
            }
            return $this->_redirect('*/*/');

        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $monolog->info($e->getMessage());
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the banner.'));
        }

        return $this->_redirect('*/*/edit', ['banner_id' => $this->getRequest()->getParam('banner_id')]);
    }
}
