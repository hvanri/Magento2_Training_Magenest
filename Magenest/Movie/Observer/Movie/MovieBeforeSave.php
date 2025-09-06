<?php
namespace Magenest\Movie\Observer\Movie;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class MovieBeforeSave implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $movie = $observer->getEvent()->getData('movie');

        $writer = new \Monolog\Handler\StreamHandler(BP . '/var/log/event.log');
        $monolog = new \Monolog\Logger('event');
        $monolog->pushHandler($writer);
        $monolog->info(json_encode($movie->getRating()));

        // Thực hiện hành động trước khi save
        $movie->setData('rating', 0);

        return $this;
    }
}
