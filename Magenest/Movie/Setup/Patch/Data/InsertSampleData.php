<?php

namespace Magenest\Movie\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InsertSampleData implements DataPatchInterface
{
    private $setup;

    public function __construct(ModuleDataSetupInterface $setup)
    {
        $this->setup = $setup;
    }

    public function apply()
    {
        $connection = $this->setup->getConnection();
        $directorTable = $this->setup->getTable('magenest_director');
        $actorTable = $this->setup->getTable('magenest_actor');
        $movieTable = $this->setup->getTable('magenest_movie');
        $movieActorTable = $this->setup->getTable('magenest_movie_actor');

        // 1. Insert directors
        $connection->insertMultiple($directorTable, [
            ['name' => 'Christopher Nolan'],
            ['name' => 'Steven Spielberg']
        ]);

        // 2. Insert actors
        $connection->insertMultiple($actorTable, [
            ['name' => 'Leonardo DiCaprio'],
            ['name' => 'Joseph Gordon-Levitt'],
            ['name' => 'Tom Hanks']
        ]);

        // 3. Insert movies
        $connection->insertMultiple($movieTable, [
            ['name' => 'Inception', 'description' => 'Dream heist', 'rating' => 9, 'director_id' => 1],
            ['name' => 'Catch Me If You Can', 'description' => 'Biographical crime', 'rating' => 8, 'director_id' => 2]
        ]);

        // 4. Insert movie-actor relations
        $connection->insertMultiple($movieActorTable, [
            ['movie_id' => 1, 'actor_id' => 1],
            ['movie_id' => 1, 'actor_id' => 2],
            ['movie_id' => 2, 'actor_id' => 3]
        ]);
    }

    public static function getDependencies() { return []; }
    public function getAliases() { return []; }
}
