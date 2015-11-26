<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Model;

use LiveChat\Api\Client;

class LivechatClientFactory
{
    /**
     * @return Client
     */
    public function createClient()
    {
        return new Client();
    }
}
