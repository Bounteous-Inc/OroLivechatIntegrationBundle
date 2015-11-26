<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Migration\Installation;

use DemacMedia\Bundle\OroLivechatIntegrationBundle\Migrations\Schema\v1_0\DemacMediaOroLivechatIntegrationBundle as v1_0;
use DemacMedia\Bundle\OroLivechatIntegrationBundle\Migrations\Schema\v1_1\DemacMediaOroLivechatIntegrationBundle as v1_1;
use DemacMedia\Bundle\OroLivechatIntegrationBundle\Migrations\Schema\v1_2\DemacMediaOroLivechatIntegrationBundle as v1_2;

class DemacMediaOroLivechatIntegrationBundleInstaller implements Installation
{
    /**
     * @inheritdoc
     */
    public function getMigrationVersion()
    {
        return 'v1_2';
    }

    /**
     * @inheritdoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        v1_0::customerTable($schema);
        v1_1::restTransportTable($schema);
        v1_2::addContactAndAccountRelations($schema);
    }
}