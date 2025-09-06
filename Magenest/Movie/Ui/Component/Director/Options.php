<?php
namespace Magenest\Movie\Ui\Component\Director;

use Magento\Framework\Data\OptionSourceInterface;
use Magenest\Movie\Model\ResourceModel\Director\CollectionFactory;

class Options implements OptionSourceInterface
{
    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];

            $collection = $this->collectionFactory->create();

            foreach ($collection as $director) {
                $this->options[] = [
                    'value' => $director->getId(),    // primary key
                    'label' => $director->getName()   // name column
                ];
            }
        }

        return $this->options;
    }
}
