<?php

namespace Magenest\Banner\Ui\Component;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magenest\Banner\Model\ResourceModel\Banner\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    protected $collection;
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
        $this->loadedData = [];

        foreach ($items as $banner) {
            $this->loadedData[$banner->getId()] = $banner->getData();
        }

        return $this->loadedData;
    }
}
