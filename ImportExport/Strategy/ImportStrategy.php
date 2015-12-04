<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\ImportExport\Strategy;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

use Doctrine\Common\Util\ClassUtils;

use Oro\Bundle\ImportExportBundle\Strategy\Import\ConfigurableAddOrReplaceStrategy;

class ImportStrategy extends ConfigurableAddOrReplaceStrategy implements
    LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function beforeProcessEntity($entity)
    {
        if ($this->logger) {
            $this->logger->info('Syncing LiveChatInc chat_id=' .$entity->getChatId());
        }

        $chatClass = 'DemacMedia\Bundle\OroLivechatIntegrationBundle\Entity\Chat';

        // if the following returns true, that means the data already exist
        $dataAlreadyExist = (bool) parent::findEntityByIdentityValues($chatClass, [
            'chatId' => $entity->getChatId()
        ]);

        return parent::beforeProcessEntity($entity);
    }

    /**
     * {@inheritdoc}
     */
    protected function afterProcessEntity($entity)
    {
        return parent::afterProcessEntity($entity);
    }
}
