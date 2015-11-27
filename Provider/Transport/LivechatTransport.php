<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport;

use LiveChat\Api\Client as Client;

use Symfony\Component\HttpFoundation\ParameterBag;
use Oro\Bundle\IntegrationBundle\Exception\InvalidConfigurationException;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use DemacMedia\Bundle\OroLivechatIntegrationBundle\Form\Type\LivechatTransportType;
use DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport\LivechatChatIterator;
use DemacMedia\Bundle\OroLivechatIntegrationBundle\Model\LivechatClientFactory;


class LivechatTransport implements TransportInterface
{
    const API_URL = 'api.livechatinc.com';
    const READ_BATCH_SIZE = 25;

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
    protected function getClientBaseUrl(ParameterBag $parameterBag)
    {
        return trim(self::API_URL . '/');
    }

    /**
     * {@inheritdoc}
     */
    protected function getClientOptions(ParameterBag $parameterBag)
    {
        $user = $parameterBag->get('api_user');
        $key  = $parameterBag->get('api_key');
        return [
            'auth' => [
                "{$key}",
                "{$user}",
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'REST';
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
        $params = [
            'output_format' => 'JSON',
            'display'       => 'full',
            'limit'         => self::READ_BATCH_SIZE
        ];
        if ($lastUpdatedAt) {
            $params['date'] = 1;
            $params['filter']['date_upd'] = sprintf(">[%s]", $lastUpdatedAt->format('Y-m-d H:i:s'));
        }

        $chats = new RestIterator($this->client, 'chats', $params);

        return $chats;
    }

}
