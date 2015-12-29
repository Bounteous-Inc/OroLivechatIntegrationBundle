<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Migrations\Schema\v1_2;

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
        self::addContactAndAccountRelations($schema);
    }
    /**
     * Add contact and account relations
     *
     * @param Schema $schema
     */
    public static function addContactAndAccountRelations(Schema $schema)
    {
        $table = $schema->getTable('demacmedia_livechat_chat');
        $table->addColumn('contact_id', 'integer', ['notnull' => false]);
        $table->addColumn('account_id', 'integer', ['notnull' => false]);
        $table->addIndex(['contact_id'], 'IDX_A8671CD1E7A12RBS', []);
        $table->addIndex(['account_id'], 'IDX_A8671CD19B6B5RBS', []);
        /** Generate foreign keys for table contacts and accounts **/
        $table = $schema->getTable('demacmedia_livechat_chat');
        $table->addForeignKeyConstraint(
            $schema->getTable('orocrm_contact'),
            ['contact_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('orocrm_account'),
            ['account_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        /** End of generate foreign keys for table contacts and accounts **/
    }
}