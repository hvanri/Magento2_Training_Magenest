<?php

namespace Magenest\Movie\Block;

use Magento\Framework\View\Element\Template;
use Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory as MovieCollectionFactory;

class Show extends Template
{
    protected $resource;
    protected $movieCollectionFactory;

    public function __construct(
        Template\Context            $context,
        MovieCollectionFactory      $movieCollectionFactory,
        array                       $data = []
    )
    {
        $this->movieCollectionFactory = $movieCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getMoviesWithDetails()
    {
        // get collection movie
        $movieCollection = $this->movieCollectionFactory->create();

        // Join director
        $movieCollection->getSelect()->joinLeft(
            ['d' => $movieCollection->getTable('magenest_director')],
            'main_table.director_id = d.director_id',
            ['director_name' => 'name']
        );

        // Join  movie_actor vs actor
        $movieCollection->getSelect()->joinLeft(
            ['ma' => $movieCollection->getTable('magenest_movie_actor')],
            'main_table.movie_id = ma.movie_id',
            []
        )->joinLeft(
            ['a' => $movieCollection->getTable('magenest_actor')],
            'ma.actor_id = a.actor_id',
            ['actors' => new \Zend_Db_Expr('GROUP_CONCAT(a.name SEPARATOR ", ")')]
        );

        // Group theo movie để gom tất cả actors
        $movieCollection->getSelect()->group('main_table.movie_id');

        $moviesData = [];

        foreach ($movieCollection as $movie) {
            $moviesData[] = [
                'movie_id' => $movie->getId(),
                'name' => $movie->getName(),
                'description' => $movie->getDescription(),
                'rating' => $movie->getRating(),
                'director_name' => $movie->getDirectorName(),
                'actors' => explode(', ', $movie->getActors())
            ];
        }

        return $moviesData;
    }
}
