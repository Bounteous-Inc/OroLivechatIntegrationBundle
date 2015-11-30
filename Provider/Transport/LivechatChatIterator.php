<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport;

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
        $this->params['page'] = $this->params['limit'];
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
                $rows[] = (array) $row;
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

