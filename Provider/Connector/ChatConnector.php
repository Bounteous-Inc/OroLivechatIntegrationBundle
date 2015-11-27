<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Connector;

use Symfony\Bridge\Doctrine\RegistryInterface;

use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Oro\Bundle\IntegrationBundle\Entity\Status;
use Oro\Bundle\IntegrationBundle\Provider\AbstractConnector;
use Oro\Bundle\IntegrationBundle\Logger\LoggerStrategy;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;

class ChatConnector extends AbstractConnector
{
    /** @var RegistryInterface */
    protected $registry;

    /**
     * @param ContextRegistry          $contextRegistry
     * @param LoggerStrategy           $logger
     * @param ConnectorContextMediator $contextMediator
     * @param RegistryInterface        $registry
     */
    public function __construct(
        ContextRegistry $contextRegistry,
        LoggerStrategy $logger,
        ConnectorContextMediator $contextMediator,
        RegistryInterface $registry)
    {
        parent::__construct($contextRegistry, $logger, $contextMediator);
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'Chats'; // this string will be translated via symfony's translator
    }

    /**
     * {@inheritdoc}
     */
    public function getImportEntityFQCN()
    {
        return 'DemacMedia\Bundle\OroLivechatIntegrationBundle\Entity\Chat';
    }

    /**
     * {@inheritdoc}
     */
    public function getImportJobName()
    {
        return 'livechat_chat_import';
    }

    /**
     * {@inheritdoc}
     */
    public function getExportJobName()
    {
        return 'livechat_chat_export';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'livechat_chat';
    }

    /**
     * {@inheritdoc}
     */
    protected function getConnectorSource()
    {
        return $this->transport->getChats($this->getLastSyncDate());
    }

    /**
     * @return \DateTime|null
     */
    protected function getLastSyncDate()
    {
        $channel = $this->contextMediator->getChannel($this->getContext());
        $status  = $this->registry->getRepository('OroIntegrationBundle:Channel')
            ->getLastStatusForConnector($channel, $this->getType(), Status::STATUS_COMPLETED);

        return $status ? $status->getDate() : null;
    }
}