<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider;

use Oro\Bundle\IntegrationBundle\Provider\ChannelInterface;
use Oro\Bundle\IntegrationBundle\Provider\IconAwareIntegrationInterface;

class LivechatChannelType implements ChannelInterface, IconAwareIntegrationInterface
{
    const TYPE = 'livechat';


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
    public function getIcon()
    {
        return 'bundles/demacmediaorolivechatintegration/img/livechat-logo.png';
    }
}

