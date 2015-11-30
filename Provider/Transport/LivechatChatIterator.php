<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport;

use LiveChat\Api\Client;

class LivechatChatIterator extends AbstractLivechatIterator
{
    /**
     * @param Client $client
     * @param array  $params
     * @param int    $batchSize
     */
    public function __construct(Client $client, $params = []) {
        parent::__construct($client, $params);
    }


    /**
     * {@inheritdoc}
     */
    protected function getResult($params)
    {
        $results = $this->client->chats->get(
            array_merge($this->params, $params)
        );

        return $results;
    }
}

