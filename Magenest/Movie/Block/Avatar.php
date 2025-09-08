<?php
namespace Magenest\Movie\Block;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;

class Avatar extends Template
{
    protected $customerSession;
    protected $storeManager;

    public function __construct(
        Template\Context $context,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    public function getAvatarUrl()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return null;
        }

        $customer = $this->customerSession->getCustomer();
        $avatar = $customer->getData('avatar');

        if ($avatar) {
            $baseUrl = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            );
            return $baseUrl . 'customer' . $avatar;
        }

        return $this->getViewFileUrl('Magenest_Movie::images/default-avatar.png');
    }

    /**
     * Get customer full name
     */
    public function getCustomerName()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return null;
        }
        $customer = $this->customerSession->getCustomer();
        return $customer->getFirstname() . ' ' . $customer->getLastname();
    }

    /**
     * Get customer email
     */
    public function getCustomerEmail()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return null;
        }
        return $this->customerSession->getCustomer()->getEmail();
    }

    /**
     * Get customer phone (custom attribute or billing address)
     */
    public function getCustomerPhone()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return null;
        }

        $customer = $this->customerSession->getCustomer();

        // Nếu có custom attribute "phone"
        if ($customer->getCustomAttribute('phone')) {
            return $customer->getCustomAttribute('phone')->getValue();
        }

        // Nếu không có thì lấy từ địa chỉ billing mặc định
        if ($customer->getDefaultBillingAddress()) {
            return $customer->getDefaultBillingAddress()->getTelephone();
        }

        return null;
    }
}
