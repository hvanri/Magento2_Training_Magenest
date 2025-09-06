<?php
namespace Magenest\Movie\Observer\Movie;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class SetRatingObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {

        $movie = $observer->getEvent()->getData('movie');
        $writer = new \Monolog\Handler\StreamHandler(BP . '/var/log/event.log');
        $monolog = new \Monolog\Logger('event');
        $monolog->pushHandler($writer);
        $monolog->info(json_encode($movie->getRating()));
        if ($movie) {
            $movie->setRating(0);
        }
    }
}
