<?php
namespace Magenest\Movie\Block\Adminhtml\Customer\Edit\Tab;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Customer\Api\CustomerRepositoryInterface;
/**
 * Customer account form block
 */
class Custom extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $_customerRepository;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        CustomerRepositoryInterface $customerRepository,
        array $data = []
    ) {
        $this->_customerRepository = $customerRepository;
        $this->_coreRegistry = $registry;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Custom Tab');
    }
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Custom Tab');
    }
    /**
     * @return bool
     */
    public function canShowTab()
    {
        if ($this->getCustomerId()) {
            return true;
        }
        return false;
    }
    /**
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }
    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }
    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }
    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }
    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('customer_tab_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Customer Avatar')]);

        // Text field ví dụ
        $fieldset->addField(
            'demo_field',
            'text',
            [
                'name' => 'demo_field',
                'data-form-part' => $this->getData('target_form'),
                'label' => __('Demo Field'),
                'title' => __('Demo Field'),
                'value' => 'test',
            ]
        );

        // Lấy customer hiện tại
        $customerId = $this->_coreRegistry->registry(
            \Magento\Customer\Controller\RegistryConstants::CURRENT_CUSTOMER_ID
        );

        $customer = null;
        if ($customerId) {
            $customer = $this->_customerRepository->getById($customerId);
        }

        // Lấy base media URL
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );

        $avatarValue = $customer && $customer->getCustomAttribute('sk_profile_pic')
            ? $customer->getCustomAttribute('sk_profile_pic')->getValue()
            : '';

        $avatarUrl = $avatarValue ? $mediaUrl . 'sk_profile_pic' . $avatarValue : '';

        $html = $avatarUrl
            ? '<img src="' . $avatarUrl . '" alt="Avatar" style="max-width:100px; max-height:100px;" />'
            : __('No avatar');

        // Field hiển thị avatar
        $fieldset->addField(
            'avatar',
            'note',
            [
                'label' => __('Avatar'),
                'text'  => $html,
            ]
        );

        $this->setForm($form);
        return $this;
    }
    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }
    /**
     * Prepare the layout.
     *
     * @return $this
     */
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        // You can call other Block also by using this function if you want to add phtml file. Otherwise, you can remove it.
//        $html .= $this->getLayout()->createBlock(
//            'Webkul\CustomerEdit\Block\Adminhtml\Customer\Edit\Tab\AdditionalBlock'
//        )->toHtml();
        return $html;
    }
}
