<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Connector;

use Symfony\Bridge\Doctrine\RegistryInterface;

use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Oro\Bundle\IntegrationBundle\Entity\Status;
use Oro\Bundle\IntegrationBundle\Provider\AbstractConnector;
use Oro\Bundle\IntegrationBundle\Logger\LoggerStrategy;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use Oro\Bundle\IntegrationBundle\Exception\RuntimeException;

class ChatConnector extends AbstractConnector
{
    /** @var RegistryInterface */
    protected $registry;

    protected $contextMediator;

    protected $contextRegistry;

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

        $this->contextMediator = $contextMediator;
        $this->contextRegistry = $contextRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'Chats';
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
        $return = null;
        $channel = $this->contextMediator->getChannel(
            $this->getContext()
        );

        $repository = $this->registry->getRepository('OroIntegrationBundle:Channel');
        $status  = $repository->getLastStatusForConnector(
            $channel,
            'chat',
            Status::STATUS_COMPLETED
        );
        
        if ($status) {
            $data = $status->getData();
            if (isset($data['endedTimestamp'])) {
                $dateObject = new \DateTime();
                $dateObject->setTimestamp($data['endedTimestamp']);
                $return = $dateObject;
            }
        }
        
        return $return;
    }


    /**
     * {@inheritdoc}
     */
    public function read()
    {
        $item = parent::read();
        
        if (null !== $item && !$this->getSourceIterator()->valid()) {
            $invalidEntries = (int) self::getContext()->getErrorEntriesCount();
            $addedEntries   = (int) self::getContext()->getAddCount();

            if ($invalidEntries < 1) {
                $this->addStatusData('endedTimestamp', $item['ended_timestamp']);
            } else {
                if ($addedEntries < 1) {
                    throw new RuntimeException('Stats: Synchronization completed but no new entries identified.');
                } else {
                    $this->addStatusData('endedTimestamp', $item['ended_timestamp']);
                }
            }
        }
        
        return $item;
    }
}
