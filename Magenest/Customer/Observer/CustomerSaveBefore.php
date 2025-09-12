<?php
namespace Magenest\Customer\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;

class CustomerSaveBefore implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $observer->getEvent()->getCustomer();

        $telephone = $customer->getTelephone();
        if ($telephone) {
            $phone = trim($telephone);

            // Nếu bắt đầu bằng +84, chuyển thành 0
            if (strpos($phone, '+84') === 0) {
                $phone = '0' . substr($phone, 3);
            }

            // Validate: phải bắt đầu bằng 0 và có đúng 10 chữ số
            if (!preg_match('/^0\d{9}$/', $phone)) {
                throw new LocalizedException(__('Phone number must start with 0 and have 10 digits.'));
            }

            // Ghi lại giá trị đã chuẩn hóa
            $customer->setCustomAttribute('telephone', $phone);
        }
    }
}
