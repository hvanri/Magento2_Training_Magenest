<?php
namespace Magenest\CustomUi\Block\Popup;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;
use Magento\SalesRule\Model\Rule;

class Promotions extends Template
{
    protected $customerSession;
    protected $ruleCollectionFactory;

    public function __construct(
        Template\Context $context,
        Session $customerSession,
        RuleCollectionFactory $ruleCollectionFactory,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Lấy danh sách ưu đãi theo nhóm khách hàng
     */
    public function getPromotionsByCustomerGroup()
    {
        $customerGroupId = $this->getCustomerGroupId();

        $collection = $this->ruleCollectionFactory->create()
            ->addFieldToFilter('is_active', 1)
            ->addCustomerGroupFilter($customerGroupId); // use built-in method

        $promotions = [];
        foreach ($collection as $rule) {
            if ($this->isRuleValid($rule)) {
                $promotions[] = [
                    'name' => $rule->getName(),
                    'description' => $rule->getDescription(),
                    'coupon_code' => $rule->getCouponCode(),
                    'discount_amount' => $rule->getDiscountAmount(),
                    'simple_action' => $rule->getSimpleAction(),
                    'from_date' => $rule->getFromDate(),
                    'to_date' => $rule->getToDate()
                ];
            }
        }

        return $promotions;
    }

    /**
     * Lấy ID nhóm khách hàng hiện tại
     */
    public function getCustomerGroupId()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer()->getGroupId();
        }
        return 0; // NOT LOGGED IN group
    }

    /**
     * Kiểm tra rule có hợp lệ không
     */
    protected function isRuleValid($rule)
    {
        $now = new \DateTime();
        $fromDate = $rule->getFromDate() ? new \DateTime($rule->getFromDate()) : null;
        $toDate = $rule->getToDate() ? new \DateTime($rule->getToDate()) : null;

        if ($fromDate && $now < $fromDate) {
            return false;
        }

        if ($toDate && $now > $toDate) {
            return false;
        }

        return true;
    }

    /**
     * Format discount text
     */
    public function getDiscountText($promotion)
    {
        $action = $promotion['simple_action'];
        $amount = $promotion['discount_amount'];

        switch ($action) {
            case 'by_percent':
                return "Giảm {$amount}%";
            case 'by_fixed':
                return "Giảm " . number_format($amount, 0, ',', '.') . "đ";
            case 'cart_fixed':
                return "Giảm " . number_format($amount, 0, ',', '.') . "đ cho đơn hàng";
            default:
                return "Ưu đãi đặc biệt";
        }
    }
}
