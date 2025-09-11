<?php
namespace Magenest\Course\Controller\Adminhtml\Material;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\StoreManagerInterface;

class File extends Action
{
    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Constructor
     */
    public function __construct(
        Context $context,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
    }

    /**
     * Upload file for course materials
     */
    public function execute()
    {
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            // Chuẩn hoá lại $_FILES để truyền vào uploader
            $files = $_FILES['product']['name']['course_materials_dynamic'] ?? [];
            foreach ($files as $index => $row) {
                if (!empty($row['file'])) {
                    $_FILES['file'] = [
                        'name'     => $_FILES['product']['name']['course_materials_dynamic'][$index]['file'],
                        'type'     => $_FILES['product']['type']['course_materials_dynamic'][$index]['file'],
                        'tmp_name' => $_FILES['product']['tmp_name']['course_materials_dynamic'][$index]['file'],
                        'error'    => $_FILES['product']['error']['course_materials_dynamic'][$index]['file'],
                        'size'     => $_FILES['product']['size']['course_materials_dynamic'][$index]['file'],
                    ];
                    break; // chỉ xử lý 1 file upload mỗi request
                }
            }

            /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
            $uploader = $this->uploaderFactory->create(['fileId' => 'file']);

            // Cho phép các loại file
            $uploader->setAllowedExtensions([
                'pdf','doc','docx','xls','xlsx','ppt','pptx',
                'jpg','jpeg','png','gif','bmp','webp',
                'mp4','avi','mov','wmv','flv','webm',
                'mp3','wav','ogg','zip','rar','txt'
            ]);

            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowCreateFolders(true);

            // Thư mục lưu file
            $mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
            $targetPath = $mediaDirectory->getAbsolutePath('course_materials/');

            // Upload
            $result = $uploader->save($targetPath);
            if (!$result) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('File cannot be saved to path: %1', $targetPath)
                );
            }

            // Trả về URL file
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $filePath = 'course_materials' . $result['file'];
            $result['url'] = $mediaUrl . ltrim($filePath, '/');

            // Bổ sung size và format
            $result['size'] = $result['size'] ?? filesize($targetPath . ltrim($result['file'], DIRECTORY_SEPARATOR));
            $result['size_formatted'] = $this->formatFileSize($result['size']);

            return $jsonResult->setData($result);

        } catch (\Exception $e) {
            return $jsonResult->setData([
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode()
            ]);
        }
    }

    /**
     * Format file size in human readable format
     */
    protected function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Check admin permissions
     */
    protected function _isAllowed()
    {
        return true;
    }
}
