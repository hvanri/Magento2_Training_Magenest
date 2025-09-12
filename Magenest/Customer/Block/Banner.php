<?php
namespace Magenest\Customer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as CustomerSession;

class Banner extends Template
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

    public function shouldShowBanner()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return false;
        }
        $customer = $this->customerSession->getCustomer();
        return (bool)$customer->getData('is_b2b');
    }
}
