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
        $table = $schema->createTable('demacmedia_livechat_chat');

        $table->addColumn('id',         'integer', ['autoincrement' => true]);
        $table->addColumn('channel_id', 'integer', ['notnull' => false]);

        $table->addColumn('chat_type',                  'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('chat_id',                    'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('chat_visitor_name',          'string', ['notnull' => false]);
        $table->addColumn('chat_visitor_id',            'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('chat_visitor_ip',            'string', ['notnull' => false, 'length' => 32]);
        $table->addColumn('chat_visitor_email',         'string', ['notnull' => false]);
        $table->addColumn('chat_visitor_city',          'string', ['notnull' => false]);
        $table->addColumn('chat_visitor_region',        'string', ['notnull' => false]);
        $table->addColumn('chat_visitor_country',       'string', ['notnull' => false]);
        $table->addColumn('chat_visitor_country_code',  'string', ['notnull' => false, 'length' => 4]);
        $table->addColumn('chat_visitor_timezone',      'string', ['notnull' => false, 'length' => 64]);
        $table->addColumn('chat_agent_name', 'string',  ['notnull' => false]);
        $table->addColumn('chat_agent_email', 'string', ['notnull' => false]);
        $table->addColumn('chat_duration',              'integer', ['notnull' => false, 'unsigned' => true]);
        $table->addColumn('chat_started',               'datetime',['notnull' => false]);
        $table->addColumn('chat_started_timestamp',     'integer', ['notnull' => false, 'unsigned' => true]);
        $table->addColumn('chat_ended_timestamp',       'integer', ['notnull' => false, 'unsigned' => true]);
        $table->addColumn('chat_ended',                 'datetime',['notnull' => false]);
        $table->addColumn('chat_start_url',             'string',  ['notnull' => false]);

        $table->addColumn('createdAt', 'datetime', ['notnull' => false]);
        $table->addColumn('updatedAt', 'datetime', ['notnull' => false]);
        $table->setPrimaryKey(['id']);

        $table->addIndex(['chat_id'],           'IDX_LC_CHAT_ID', []);
        $table->addIndex(['chat_visitor_name'], 'IDX_LC_VISITOR_NAME', []);
        $table->addIndex(['chat_visitor_id'],   'IDX_LC_VISITOR_ID', []);
        $table->addIndex(['chat_visitor_ip'],   'IDX_LC_VISITOR_IP', []);
        $table->addIndex(['chat_visitor_email'],'IDX_LC_VISITOR_EMAIL', []);
        $table->addIndex(['chat_visitor_city'], 'IDX_LC_VISITOR_CITY', []);
        $table->addIndex(['chat_visitor_region'], 'IDX_LC_VISITOR_REGION', []);
        $table->addIndex(['chat_visitor_country'], 'IDX_LC_VISITOR_COUNTRY', []);
        $table->addIndex(['chat_visitor_country_code'], 'IDX_LC_VISITOR_COUNTRY_CODE', []);
        $table->addIndex(['chat_visitor_timezone'], 'IDX_LC_VISITOR_TIMEZONE', []);
        $table->addIndex(['chat_agent_name'],   'IDX_LC_AGENT_NAME', []);
        $table->addIndex(['chat_agent_email'],  'IDX_LC_AGENT_EMAIL', []);
        $table->addIndex(['chat_duration'],     'IDX_LC_DURATION', []);
        $table->addIndex(['chat_started'],      'IDX_LC_STARTED', []);
        $table->addIndex(['chat_started_timestamp'], 'IDX_LC_STARTED_TIMESTAMP', []);
        $table->addIndex(['chat_ended_timestamp'], 'IDX_LC_ENDED_TIMESTAMP', []);
        $table->addIndex(['chat_ended'],        'IDX_LC_ENDED', []);
        $table->addIndex(['chat_start_url'],        'IDX_LC_START_URL', []);

        $table = $schema->getTable('demacmedia_livechat_chat');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_integration_channel'),
            ['channel_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
    }
}