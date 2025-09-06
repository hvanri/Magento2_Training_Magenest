<?php
namespace Magenest\Movie\Observer\Movie;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Cache\TypeListInterface;

class ChangeTextField implements ObserverInterface
{
    protected $configWriter;
    protected $scopeConfig;
    protected $cacheTypeList;

    public function __construct(
        WriterInterface $configWriter,
        ScopeConfigInterface $scopeConfig,
        TypeListInterface $cacheTypeList,
    ) {
        $this->configWriter = $configWriter;
        $this->scopeConfig = $scopeConfig;
        $this->cacheTypeList = $cacheTypeList;
    }

    public function execute(Observer $observer)
    {
        $scope = 'default';
        $scopeId = 0;

        if ($observer->getEvent()->getWebsite()) {
            $scope = 'websites';
            $scopeId = $observer->getEvent()->getWebsite()->getId();
        } elseif ($observer->getEvent()->getStore()) {
            $scope = 'stores';
            $scopeId = $observer->getEvent()->getStore()->getId();
        }

        $path = 'movie/general/text_field';
        $currentValue = $this->scopeConfig->getValue($path, $scope, $scopeId);

        if ($currentValue === 'Ping') {
            $this->configWriter->save($path, 'Pong', $scope, $scopeId);
            $this->cacheTypeList->cleanType('config'); ///update
        }
    }
}
