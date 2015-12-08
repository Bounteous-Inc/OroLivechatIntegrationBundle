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
        return parent::beforeProcessEntity($entity);;
    }


    /**
     * {@inheritdoc}
     */
    public function process($entity)
    {
        $entity = $this->beforeProcessEntity($entity);

        $chatClass = 'DemacMedia\Bundle\OroLivechatIntegrationBundle\Entity\Chat';
        $existingEntity = (bool) parent::findEntityByIdentityValues($chatClass, [
            'chatId' => $entity->getChatId()
        ]);

        if (!$existingEntity) {
            if ($this->logger) {
                $this->logger->info('Syncing LiveChatInc chat_id=' .$entity->getChatId());
            }

            $entity = $this->processEntity($entity, true, true, $this->context->getValue('itemData'));

        } else {
            if ($this->logger) {
                $this->logger->info('Ignoring existing ' .$entity->getChatId());
            }

            return null;

        }

        $entity = $this->afterProcessEntity($entity);
        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    protected function afterProcessEntity($entity)
    {
        return parent::afterProcessEntity($entity);
    }
}
