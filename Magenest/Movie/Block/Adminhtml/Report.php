<?php
namespace Magenest\Movie\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollection;
use Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory as InvoiceCollection;
use Magento\Sales\Model\ResourceModel\Order\Creditmemo\CollectionFactory as CreditmemoCollection;

class Report extends Template
{
    protected $moduleList;
    protected $customerCollection;
    protected $productCollection;
    protected $orderCollection;
    protected $invoiceCollection;
    protected $creditmemoCollection;

    public function __construct(
        Template\Context $context,
        ModuleListInterface $moduleList,
        CustomerCollection $customerCollection,
        ProductCollection $productCollection,
        OrderCollection $orderCollection,
        InvoiceCollection $invoiceCollection,
        CreditmemoCollection $creditmemoCollection,
        array $data = []
    ) {
        $this->moduleList        = $moduleList;
        $this->customerCollection = $customerCollection;
        $this->productCollection  = $productCollection;
        $this->orderCollection    = $orderCollection;
        $this->invoiceCollection  = $invoiceCollection;
        $this->creditmemoCollection = $creditmemoCollection;
        parent::__construct($context, $data);
    }

    public function getMagentoModulesCount()
    {
        $modules = $this->moduleList->getAll();
        $magentoModules = array_filter(array_keys($modules), function($m) {
            return strpos($m, 'Magento_') === 0;
        });
        return count($magentoModules);
    }

    public function getThirdPartyModulesCount()
    {
        $modules = $this->moduleList->getAll();
        $thirdParty = array_filter(array_keys($modules), function($m) {
            return strpos($m, 'Magento_') !== 0;
        });
        return count($thirdParty);
    }

    public function getCustomersCount()
    {
        return $this->customerCollection->create()->getSize();
    }

    public function getProductsCount()
    {
        return $this->productCollection->create()->getSize();
    }

    public function getOrdersCount()
    {
        return $this->orderCollection->create()->getSize();
    }

    public function getInvoicesCount()
    {
        return $this->invoiceCollection->create()->getSize();
    }

    public function getCreditmemosCount()
    {
        return $this->creditmemoCollection->create()->getSize();
    }
}
