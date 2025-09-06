<?php
namespace Magenest\Movie\Ui\Component\Form\Movie;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        foreach ($items as $movie) {
            $this->loadedData[$movie->getId()] = $movie->getData();
        }

        // Rất quan trọng cho trang Add New:
        if (empty($this->loadedData)) {
            $this->loadedData[null] = [];
        }

        return $this->loadedData;
    }
}
