<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Migrations\Schema\v1_0;

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
        self::customerTable($schema);
    }

    /**
     * Generate table demacmedia_livechat_chat
     *
     * @param Schema $schema
     */
    public static function customerTable(Schema $schema)
    {
        /** Generate table demacmedia_livechat_chats **/
        $table = $schema->createTable('demacmedia_livechat_chat');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('channel_id', 'integer', ['notnull' => false]);
        $table->addColumn('remote_id', 'integer', ['unsigned' => true]);
        $table->addColumn('name_prefix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('first_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('middle_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('last_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('name_suffix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('gender', 'string', ['notnull' => false, 'length' => 8]);
        $table->addColumn('birthday', 'datetime', ['notnull' => false]);
        $table->addColumn('email', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('createdAt', 'datetime', []);
        $table->addColumn('updatedAt', 'datetime', []);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['remote_id', 'channel_id'], 'unq_remote_id_channel_id');
        $table->addIndex(['channel_id'], 'IDX_5FB5485872F5ARBS', []);
        /** End of generate table demacmedia_livechat_chat **/

        /** Generate foreign keys for table demacmedia_livechat_chats **/
        $table = $schema->getTable('demacmedia_livechat_chat');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_channel'),
            ['channel_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        /** End of generate foreign keys for table demacmedia_livechat_chat **/
    }
}