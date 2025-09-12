<?php
namespace Magenest\Customer\Block\Account;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as CustomerSession;

class B2BInfo extends Template
{
    protected $customerSession;

    public function __construct(
        Template\Context $context,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getB2BStatusLabel()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return null;
        }

        $customer = $this->customerSession->getCustomer();
        $isB2B = (int) $customer->getData('is_b2b');

        return $isB2B ? __('B2B Account') : __('Regular Account');
    }
}
