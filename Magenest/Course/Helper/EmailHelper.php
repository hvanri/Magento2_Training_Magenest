<?php
namespace Magenest\Course\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class EmailHelper extends AbstractHelper
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function isEmailEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            'course_email/purchase_success/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getEmailTemplate()
    {
        return $this->scopeConfig->getValue(
            'course_email/purchase_success/template',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function formatCourseMaterials($materials)
    {
        $formatted = [];

        if (is_array($materials)) {
            foreach ($materials as $material) {
                if (!empty($material['title'])) {
                    $formatted[] = [
                        'title' => $material['title'],
                        'description' => $material['content'] ?? '',
                        'has_file' => !empty($material['file']),
                        'file_name' => $material['file'] ?? ''
                    ];
                }
            }
        }

        return $formatted;
    }
}
