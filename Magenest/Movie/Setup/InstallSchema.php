<?php
namespace Magenest\Movie\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $connection = $setup->getConnection();

        /**
         * Table: magenest_director
         */
        if (!$setup->tableExists('magenest_director')) {
            $table = $connection->newTable($setup->getTable('magenest_director'))
                ->addColumn(
                    'director_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Director ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Director Name'
                )
                ->setComment('Director Table');
            $connection->createTable($table);
        }

        /**
         * Table: magenest_actor
         */
        if (!$setup->tableExists('magenest_actor')) {
            $table = $connection->newTable($setup->getTable('magenest_actor'))
                ->addColumn(
                    'actor_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Actor ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Actor Name'
                )
                ->setComment('Actor Table');
            $connection->createTable($table);
        }

        /**
         * Table: magenest_movie
         */
        if (!$setup->tableExists('magenest_movie')) {
            $table = $connection->newTable($setup->getTable('magenest_movie'))
                ->addColumn(
                    'movie_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Movie ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Movie Name'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    '2M',
                    [],
                    'Movie Description'
                )
                ->addColumn(
                    'rating',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => 0],
                    'Movie Rating'
                )
                ->addColumn(
                    'director_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Director ID'
                )
                ->addForeignKey(
                    $setup->getFkName('magenest_movie', 'director_id', 'magenest_director', 'director_id'),
                    'director_id',
                    $setup->getTable('magenest_director'),
                    'director_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Movie Table');
            $connection->createTable($table);
        }

        /**
         * Table: magenest_movie_actor (many-to-many)
         */
        if (!$setup->tableExists('magenest_movie_actor')) {
            $table = $connection->newTable($setup->getTable('magenest_movie_actor'))
                ->addColumn(
                    'movie_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Movie ID'
                )
                ->addColumn(
                    'actor_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Actor ID'
                )
                ->addForeignKey(
                    $setup->getFkName('magenest_movie_actor', 'movie_id', 'magenest_movie', 'movie_id'),
                    'movie_id',
                    $setup->getTable('magenest_movie'),
                    'movie_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $setup->getFkName('magenest_movie_actor', 'actor_id', 'magenest_actor', 'actor_id'),
                    'actor_id',
                    $setup->getTable('magenest_actor'),
                    'actor_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Movie-Actor Relation Table');
            $connection->createTable($table);
        }

        $setup->endSetup();
    }
}
