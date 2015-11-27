<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\ImportExport\Reader;

use Doctrine\ORM\Query;

use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\IntegrationBundle\Reader\EntityReaderById;

class EntityReader extends EntityReaderById
{
    const REMOTE_ID_FIELD = 'remoteId';

    /**
     * {@inheritdoc}
     */
    protected function ensureInitialized(ContextInterface $context)
    {
        if (null !== $this->qb) {
            $this->qb->andWhere(
                $this->qb->expr()->isNull('o.' . self::REMOTE_ID_FIELD)
            );
        }

        parent::ensureInitialized($context);
    }
}
