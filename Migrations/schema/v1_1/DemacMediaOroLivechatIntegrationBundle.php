<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class DemacMediaOroLivechatIntegrationBundle implements Migration
{
    /**
     * @inheritdoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        self::restTransportTable($schema);
    }

    /**
     * Generate table demacmedia_livechat_transport
     *
     * @param Schema $schema
     */
    public static function restTransportTable(Schema $schema)
    {
        $table = $schema->createTable('demacmedia_livechat_transport');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('livechat_rest_api_user', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('livechat_rest_api_key',  'string', ['notnull' => false, 'length' => 255]);
        $table->setPrimaryKey(['id']);
    }
}