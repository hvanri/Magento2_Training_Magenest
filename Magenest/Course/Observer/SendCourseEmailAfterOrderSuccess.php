<?php

namespace Magenest\Course\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class SendCourseEmailAfterOrderSuccess implements ObserverInterface
{
    const XML_PATH_EMAIL_ENABLED = 'course_email/purchase_success/enabled';
    const XML_PATH_EMAIL_TEMPLATE = 'course_email/purchase_success/template';
    const XML_PATH_EMAIL_SENDER = 'course_email/purchase_success/sender';

    protected $transportBuilder;
    protected $inlineTranslation;
    protected $storeManager;
    protected $scopeConfig;

    public function __construct(
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        // Kiểm tra xem có sản phẩm khóa học nào không
        $courseItems = $this->getCourseItemsHtmlFromOrder($order);

        if (empty($courseItems)) {
            return;
        }

        // Kiểm tra email có được bật không
        if (!$this->scopeConfig->isSetFlag(
            self::XML_PATH_EMAIL_ENABLED,
            ScopeInterface::SCOPE_STORE
        )) {
            return;
        }

        $this->sendCourseEmail($order, $courseItems);
    }

    protected function getCourseItemsHtmlFromOrder($order): string
    {
        $coursesHtml = [];

        foreach ($order->getAllVisibleItems() as $item) {
            $product = $item->getProduct();

            // Chỉ lấy sản phẩm khóa học
            if ($product->getAttributeSetId() != 17) {
                continue;
            }

            $coursesHtml[] = $this->renderCourseHtml($product);
        }

        if (empty($coursesHtml)) {
            return '<p>No course materials available.</p>';
        }

        return implode('', $coursesHtml);
    }

    protected function renderCourseHtml($product): string
    {
        $courseName = htmlspecialchars($product->getName());
        $courseSku  = htmlspecialchars($product->getSku());

        $materialsHtml = $this->getCourseMaterialsHtml($product);

        return "<h3>{$courseName} ({$courseSku})</h3><ul>{$materialsHtml}</ul>";
    }

    protected function getCourseMaterialsHtml($product): string
    {
        $attributes = ['course_file_materials', 'course_text_materials'];
        $materialsHtml = [];

        foreach ($attributes as $attributeCode) {
            $data = $product->getData($attributeCode);

            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            if (!is_array($data)) {
                continue;
            }

            // Lấy inner array nếu cần
            if (isset($data[$attributeCode]) && is_array($data[$attributeCode])) {
                $data = $data[$attributeCode];
            }

            foreach ($data as $material) {
                $materialsHtml[] = $this->renderMaterialHtml($material);
            }
        }

        if (empty($materialsHtml)) {
            return '<li>No course materials available.</li>';
        }

        return implode('', $materialsHtml);
    }

    protected function renderMaterialHtml(array $material): string
    {
        $title   = $material['title'] ?? '';
        $content = $material['content'] ?? '';
        $files   = $material['file'] ?? [];

        $html = '<li>';
        if ($title) {
            $html .= '<strong>' . htmlspecialchars($title) . '</strong>: ';
        }
        $html .= htmlspecialchars($content);

        if (is_array($files)) {
            foreach ($files as $fileItem) {
                if (!empty($fileItem['url'])) {
                    $html .= ' <a href="' . htmlspecialchars($fileItem['url']) . '">Download</a>';
                }
            }
        }

        $html .= '</li>';

        return $html;
    }

    protected function sendCourseEmail($order, string $courseItemsHtml): void
    {
        try {
            $this->inlineTranslation->suspend();

            $store = $this->storeManager->getStore();
            $customerName = trim($order->getCustomerFirstname() . ' ' . $order->getCustomerLastname());

            $templateVars = [
                'customer_name' => $customerName,
                'customer_email' => $order->getCustomerEmail(),
                'order_id' => $order->getIncrementId(),
                'course_materials_html' => $courseItemsHtml,
                'store' => $store
            ];

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($this->getEmailTemplate())
                ->setTemplateOptions([
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $store->getId()
                ])
                ->setTemplateVars($templateVars)
                ->setFrom($this->getEmailSender())
                ->addTo($order->getCustomerEmail(), $customerName)
                ->getTransport();

            $transport->sendMessage();
        } catch (\Exception $e) {
            // Log lỗi
            error_log('Course email sending failed: ' . $e->getMessage());
        } finally {
            $this->inlineTranslation->resume();
        }
    }

    protected function getEmailTemplate(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        ) ?: 'course_purchase_success_template';
    }

    protected function getEmailSender(): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE
        ) ?: 'general';
    }
}
