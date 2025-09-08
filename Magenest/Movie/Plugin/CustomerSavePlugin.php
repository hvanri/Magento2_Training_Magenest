<?php
namespace Magenest\Movie\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\Uploader;

class CustomerSavePlugin
{
    protected $filesystem;
    protected $imageUploader;

    public function __construct(
        Filesystem $filesystem,
        \Magenest\Movie\Model\ImageUploader $imageUploader //
    ) {
        $this->filesystem = $filesystem;
        $this->imageUploader = $imageUploader;
    }

    public function beforeSave(
        \Magento\Customer\Api\CustomerRepositoryInterface $subject,
        \Magento\Customer\Api\Data\CustomerInterface $customer
    ) {
        $logger = new \Monolog\Logger('avatar_save');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler(BP . '/var/log/avatar_save.log'));

        try {
            $avatarAttr = $customer->getCustomAttribute('avatar');
            $avatarValue = $avatarAttr ? trim($avatarAttr->getValue()) : null;

            if ($avatarValue) {
                $filename = basename($avatarValue); // tránh double path
                $hardPath = '/avatar/' . $filename;

                $customer->setCustomAttribute('avatar', $hardPath);
                $this->imageUploader->moveFileFromTmp($avatarValue);

                $logger->info('Normalized avatar', [
                    'original' => $avatarValue,
                    'final'    => $hardPath
                ]);
            }
        } catch (\Exception $e) {
            $logger->error('Avatar save failed (hard normalize)', [
                'customer_id' => $customer->getId(),
                'message'     => $e->getMessage()
            ]);
        }

        return [$customer];
    }
}
