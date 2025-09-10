<?php
namespace Magenest\Course\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_COURSE_ACCESS_ENABLED = 'course_access/general/enabled';
    const XML_PATH_DEFAULT_ACCESS_DAYS = 'course_access/general/default_access_days';
    const XML_PATH_ACCESS_RULES = 'course_access/customer_group_rules/access_rules';
    const XML_PATH_NOTIFICATION_ENABLED = 'course_access/advanced/access_notification';
    const XML_PATH_NOTIFICATION_DAYS = 'course_access/advanced/notification_days_before';

    /**
     * Check if course access control is enabled
     */
    public function isEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_COURSE_ACCESS_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get default access days
     */
    public function getDefaultAccessDays($storeId = null)
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_ACCESS_DAYS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get access rules configuration
     */
    public function getAccessRules($storeId = null)
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_ACCESS_RULES,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if ($value) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * Get access days for specific customer group
     */
    public function getAccessDaysForGroup($customerGroupId, $storeId = null)
    {
        if (!$this->isEnabled($storeId)) {
            return 0; // Unlimited if disabled
        }

        $rules = $this->getAccessRules($storeId);

        foreach ($rules as $rule) {
            if ($rule['customer_group_id'] == $customerGroupId) {
                return (int) $rule['access_days'];
            }
        }

        // Return default if no specific rule found
        return $this->getDefaultAccessDays($storeId);
    }

    /**
     * Check if notifications are enabled
     */
    public function isNotificationEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_NOTIFICATION_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get notification days before expiry
     */
    public function getNotificationDaysBefore($storeId = null)
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_NOTIFICATION_DAYS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Calculate access end date
     */
    public function calculateAccessEndDate($customerGroupId, $purchaseDate = null, $storeId = null)
    {
        $accessDays = $this->getAccessDaysForGroup($customerGroupId, $storeId);

        if ($accessDays == 0) {
            return null; // Unlimited access
        }

        $purchaseDate = $purchaseDate ?: new \DateTime();
        if (is_string($purchaseDate)) {
            $purchaseDate = new \DateTime($purchaseDate);
        }

        return $purchaseDate->add(new \DateInterval('P' . $accessDays . 'D'));
    }

    /**
     * Check if customer has access
     */
    public function hasAccess($customerGroupId, $purchaseDate = null, $storeId = null)
    {
        if (!$this->isEnabled($storeId)) {
            return true; // Always allow if disabled
        }

        $accessDays = $this->getAccessDaysForGroup($customerGroupId, $storeId);

        if ($accessDays == 0) {
            return true; // Unlimited access
        }

        if (!$purchaseDate) {
            return true; // Assume access if no purchase date
        }

        $endDate = $this->calculateAccessEndDate($customerGroupId, $purchaseDate, $storeId);
        return $endDate ? (new \DateTime() <= $endDate) : true;
    }
}
