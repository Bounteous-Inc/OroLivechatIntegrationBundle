<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport;

use DemacMedia\Bundle\OroLivechatIntegrationBundle\ImportExport\Serializer\DateTimeNormalizer;
use LiveChat\Api\Client;
use Oro\Bundle\IntegrationBundle\Provider\AbstractConnector;

class LivechatChatIterator extends AbstractLivechatIterator
{
    /**
     * @var string
     */
    protected $resource;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var string|null
     */
    protected $nextPageUrl;

    /**
     * @param Client $client
     * @param string $resource
     * @param array $params
     */
    public function __construct(Client $client, $resource, $params = [])
    {
        parent::__construct($client);
        $this->resource = $resource;
        $this->params = $params;
    }


    /**
     * {@inheritdoc}
     */
    protected function loadPage(Client $client, $params)
    {
        $data = $this->getResult($this->params, $this->resource);

        return $data;
    }


    /**
     * {@inheritdoc}
     */
    protected function getRowsFromPageData($data = [])
    {
        if (isset($data) && is_array($data)) {
            $rows = [];
            foreach($data as $row) {
                $rowArray = (array) $row;
                $rowArray['custom_visitor_ip']           = isset($rowArray['visitor']->ip)? $rowArray['visitor']->ip: '';
                $rowArray['custom_visitor_city']         = isset($rowArray['visitor']->city)? $rowArray['visitor']->city: '';
                $rowArray['custom_visitor_region']       = isset($rowArray['visitor']->region)? $rowArray['visitor']->region: '';
                $rowArray['custom_visitor_country']      = isset($rowArray['visitor']->country)? $rowArray['visitor']->region: '';
                $rowArray['custom_visitor_country_code'] = isset($rowArray['visitor']->country_code)? $rowArray['visitor']->country_code: '';
                $rowArray['custom_visitor_timezone']     = isset($rowArray['visitor']->timezone)? $rowArray['visitor']->timezone: '';
                $rowArray['custom_agent_name']           = isset($rowArray['agents'][0]->display_name)? $rowArray['agents'][0]->display_name: '';
                $rowArray['custom_agent_email']          = isset($rowArray['agents'][0]->email)? $rowArray['agents'][0]->email: '';
                $rowArray['custom_visitor_email']        = isset($rowArray['prechat_survey'][1]->value)? $rowArray['prechat_survey'][1]->value: '';

                $rows[] = $rowArray;
            }
            return $rows;
        } else {
            return null;
        }
    }


    /**
     * {@inheritdoc}
     */
    protected function getTotalCountFromPageData(array $data, $previousValue)
    {
        if (isset($data)) {
            return count($data);
        } else {
            return $previousValue;
        }
    }
    

    /**
     * {@inheritdoc}
     */
    protected function getResult($params, $resource = 'chats')
    {
        $results = $this->client->$resource->get(
            array_merge($this->params, $params)
        );

        return $results;
    }
}
