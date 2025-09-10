<?php
namespace Magenest\Movie\Block\Adminhtml\Customer\Edit\Tab;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Customer\Model\CustomerFactory;
use Magento\Store\Model\StoreManagerInterface;

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
     * @var CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_systemStore = $systemStore;
        $this->_customerFactory = $customerFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function getCustomerId()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    public function getTabLabel()
    {
        return __('Custom Tab');
    }

    public function getTabTitle()
    {
        return __('Custom Tab');
    }

    public function canShowTab()
    {
        return (bool) $this->getCustomerId();
    }

    public function isHidden()
    {
        return !$this->getCustomerId();
    }

    public function getTabClass()
    {
        return '';
    }

    public function getTabUrl()
    {
        return '';
    }

    public function isAjaxLoaded()
    {
        return false;
    }

    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }

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

        $avatarUrl = '';
        $customerId = $this->getCustomerId();
        if ($customerId) {
            /** @var \Magento\Customer\Model\Customer $customer */
            $customer = $this->_customerFactory->create()->load($customerId);

            $avatarValue = $customer->getData('sk_profile_pic'); // lấy column trực tiếp
            if ($avatarValue) {
                $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                );
                $avatarUrl = $mediaUrl . 'sk_profile_pic/' . $avatarValue;
            }
        }

        $html = $avatarUrl
            ? '<img src="' . $avatarUrl . '" alt="Avatar" style="max-width:100px; max-height:100px;" />'
            : __('No avatar');

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

    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();
            return parent::_toHtml();
        }
        return '';
    }

    public function getFormHtml()
    {
        return parent::getFormHtml();
    }
}
