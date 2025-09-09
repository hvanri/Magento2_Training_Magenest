<?php
namespace Magenest\Movie\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;

class Export extends Action
{
    protected $fileFactory;
    protected $orderCollectionFactory;
    protected $addressRenderer;

    public function __construct(
        Action\Context $context,
        FileFactory $fileFactory,
        OrderCollectionFactory $orderCollectionFactory,
        AddressRenderer $addressRenderer
    ) {
        parent::__construct($context);
        $this->fileFactory = $fileFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->addressRenderer = $addressRenderer;
    }

    /**
     * Execute export CSV
     */
    public function execute()
    {
        // Lấy các order được chọn (mass action) hoặc tất cả
        $orderIds = $this->getRequest()->getParam('selected', []);
        $collection = $this->orderCollectionFactory->create();
        if (!empty($orderIds)) {
            $collection->addFieldToFilter('entity_id', ['in' => $orderIds]);
        }

        $fileName = 'order_items_export.csv';
        $data = [];

        // Header CSV
        $data[] = [
            'Order ID',
            'Increment ID',
            'Order Date',
            'Customer Name',
            'Customer Email',
            'SKU',
            'Product Name',
            'Qty Ordered',
            'Price',
            'Row Total',
            'Shipping Address'
        ];

        foreach ($collection as $order) {
            $shippingAddress = '';
            if ($order->getShippingAddress()) {
                $shippingAddress = $this->addressRenderer->format($order->getShippingAddress(), 'text');
            }

            foreach ($order->getAllVisibleItems() as $item) {
                $data[] = [
                    $order->getEntityId(),
                    $order->getIncrementId(),
                    $order->getCreatedAt(),
                    $order->getCustomerName(),
                    $order->getCustomerEmail(),
                    $item->getSku(),
                    $item->getName(),
                    $item->getQtyOrdered(),
                    $item->getPrice(),
                    $item->getRowTotal(),
                    $shippingAddress
                ];
            }
        }

        // Chuyển array thành CSV string
        $csvData = '';
        foreach ($data as $row) {
            $escaped = array_map(function($field) {
                return str_replace('"', '""', $field); // escape double quotes
            }, $row);
            $csvData .= '"' . implode('","', $escaped) . "\"\n";
        }

        // Tạo file CSV
        return $this->fileFactory->create(
            $fileName,
            $csvData,
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
            'text/csv'
        );
    }
}
