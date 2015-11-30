<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport;

use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Exception\InvalidConfigurationException;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use DemacMedia\Bundle\OroLivechatIntegrationBundle\Form\Type\LivechatTransportType;
use DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport\LivechatChatIterator;
use DemacMedia\Bundle\OroLivechatIntegrationBundle\Model\LivechatClientFactory;
use LiveChat\Api\Client;

class LivechatTransport implements TransportInterface
{
    /**
     * @var LivechatClientFactory
     */
    protected $livechatClientFactory;

    /**
     * @var Client
     */
    protected $client;


    /**
     * @param LivechatClientFactory $livechatClientFactory
     */
    public function __construct(LivechatClientFactory $livechatClientFactory)
    {
        $this->livechatClientFactory = $livechatClientFactory;
    }


    /**
     * {@inheritdoc}
     */
    public function init(Transport $transportEntity)
    {
        $apiUser = $transportEntity->getSettingsBag()->get('api_user');
        if (empty($apiUser)) {
            throw new InvalidConfigurationException('API User isn\'t set.');
        }

        $apiKey = $transportEntity->getSettingsBag()->get('api_key');
        if (empty($apiKey)) {
            throw new InvalidConfigurationException('API Key isn\'t set.');
        }

        $this->client = $this->livechatClientFactory->createClient(
            $apiUser,
            $apiKey
        );

        return $this->client;
    }


    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'demacmedia.livechat.channel_type.label';
    }


    /**
     * {@inheritdoc}
     */
    public function getSettingsFormType()
    {
        return LivechatTransportType::NAME;
    }


    /**
     * {@inheritdoc}
     */
    public function getSettingsEntityFQCN()
    {
        return 'DemacMedia\Bundle\OroLivechatIntegrationBundle\Entity\LivechatTransport';
    }


    /**
     * Get Livechat chats data.
     *
     * @param \DateTime $lastUpdatedAt
     * @return \Iterator
     * @throws RestException
     */
    public function getChats($lastUpdatedAt)
    {
        $params = [];

        if ($lastUpdatedAt) {
            $params['date_from'] = sprintf("%s", $lastUpdatedAt->format('Y-m-d'));
        }

        $chats = new LivechatChatIterator($this->client, 'chats', $params);

        return $chats;
    }
}



