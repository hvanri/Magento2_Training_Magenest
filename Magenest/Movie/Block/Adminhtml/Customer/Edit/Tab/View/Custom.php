<?php

namespace Magenest\Movie\Block\Adminhtml\Customer\Edit\Tab\View;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use Magento\Framework\Registry;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Custom extends Extended
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param BackendHelper $backendHelper
     * @param CollectionFactory $collectionFactory
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        CollectionFactory $collectionFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Grid initialization
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('customer_purchased_products_grid');
        $this->setDefaultSort('entity_id', 'desc');
        $this->setPagerVisibility(true);
        $this->setFilterVisibility(true);
        $this->setSortable(true);
    }

    /**
     * Prepare collection for grid
     *
     * @return $this
     */
    protected function _prepareCollection()
    {
        $customerId = $this->_coreRegistry->registry(\Magento\Customer\Controller\RegistryConstants::CURRENT_CUSTOMER_ID);

        $collection = $this->_collectionFactory->create();

        // Join order items & orders để lấy sản phẩm của customer
        $collection->getSelect()->join(
            ['order_item' => $collection->getTable('sales_order_item')],
            'e.entity_id = order_item.product_id',
            []
        )->join(
            ['order_table' => $collection->getTable('sales_order')],
            'order_table.entity_id = order_item.order_id',
            []
        )->where('order_table.customer_id = ?', $customerId)
            ->group('e.entity_id');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'index'  => 'entity_id',
                'type'   => 'number',
                'width'  => '80px'
            ]
        );

        $this->addColumn(
            'name',
            [
                'header' => __('Product Name'),
                'index'  => 'name',
                'type'   => 'text'
            ]
        );

        $this->addColumn(
            'sku',
            [
                'header' => __('SKU'),
                'index'  => 'sku',
                'type'   => 'text'
            ]
        );

        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'index'  => 'price',
                'type'   => 'price',
                'currency_code' => $this->_storeManager->getStore()->getCurrentCurrencyCode()
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Show grid header
     *
     * @return bool
     */
    public function getHeadersVisibility()
    {
        $collection = $this->getCollection();
        return $collection ? $collection->getSize() > 0 : false;
    }

    /**
     * Row URL for grid
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('catalog/product/edit', ['id' => $row->getId()]);
    }
}
