<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport;

use DemacMedia\Bundle\OroLivechatIntegrationBundle\ImportExport\Serializer\DateTimeNormalizer;
use LiveChat\Api\Client;


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
    protected function loadPage(Client $client)
    {
        $data = $this->getResult($this->params, $this->resource);

        return $data;
    }


    /**
     * {@inheritdoc}
     */
    protected function getRowsFromPageData(array $data = [])
    {
        if (isset($data) && is_array($data)) {
            $rows = [];
            foreach($data as $row) {
                $rowArray = (array) $row;
                $rowArray['custom_visitor_ip'] = $rowArray['visitor']->ip;
                $rowArray['custom_visitor_city'] = $rowArray['visitor']->city;
                $rowArray['custom_visitor_region'] = $rowArray['visitor']->region;
                $rowArray['custom_visitor_country'] = $rowArray['visitor']->country;
                $rowArray['custom_visitor_country_code'] = $rowArray['visitor']->country_code;
                $rowArray['custom_visitor_timezone'] = $rowArray['visitor']->timezone;

                if(isset($rowArray['agents'][0]->display_name)) {
                    $rowArray['custom_agent_name'] = $rowArray['agents'][0]->display_name;
                } else {
                    $rowArray['custom_agent_name'] = '';
                }

                if(isset($rowArray['agents'][0]->display_email)) {
                    $rowArray['custom_agent_email'] = $rowArray['agents'][0]->email;
                } else {
                    $rowArray['custom_agent_email'] = '';
                }

                if(isset($rowArray['prechat_survey'][1]->value)) {
                    $rowArray['custom_visitor_email'] = $rowArray['prechat_survey'][1]->value;
                } else {
                    $rowArray['custom_visitor_email'] = '';
                }

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
    protected function getResult($params, $resource)
    {
        $results = $this->client->$resource->get(
            array_merge($this->params, $params)
        );

        return $results->$resource;
    }
}

