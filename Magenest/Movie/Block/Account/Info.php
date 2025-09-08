<?php
namespace Magenest\Movie\Block\Account;

use Magento\Framework\View\Element\Template;

use Magento\Customer\Model\Session;

class Info extends Template
{
    protected $customerSession;

    public function __construct(
        Template\Context $context,
        Session $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getCustomer()
    {
        return $this->customerSession->getCustomer();
    }

    public function getAvatarUrl()
    {
        $customer = $this->getCustomer();
        $avatar = $customer->getCustomAttribute('avatar') ? $customer->getCustomAttribute('avatar')->getValue() : null;
        if ($avatar) {
            return $this->getUrl('pub/media/customer/'.$avatar);
        }
        return false;
    }
}
