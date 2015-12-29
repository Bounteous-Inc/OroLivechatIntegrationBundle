<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Model;

use LiveChat\Api\Client as Client;

class LivechatClientFactory
{
    /**
     * @return Client
     */
    public function createClient($apiUser, $apiKey)
    {
        $livechatClient = new Client($apiUser, $apiKey);

        return $livechatClient;
    }
}
