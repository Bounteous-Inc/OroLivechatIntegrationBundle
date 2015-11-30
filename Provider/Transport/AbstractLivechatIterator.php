<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport;

use LiveChat\Api\Client;

abstract class AbstractLivechatIterator implements \Iterator
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var mixed
     */
    protected $current = null;

    /**
     * @var int
     */
    protected $page = -1;

    /**
     * @var int
     */
    protected $total = -1;


    /**
     * @param Client $client
     * @param array  $params
     * @param int    $batchSize
     */
    public function __construct(Client $client, $params = []) {
        $this->client  = $client;
        $defaultParams = [];
        $this->params    = array_merge($defaultParams, $params);
    }


    /**
     * {@inheritdoc}
     */
    public function rewind() {
        $this->current = null;
        $this->page    = -1;
        $this->total   = -1;
        $this->data    = null;

        $this->next();
    }


    /**
     * {@inheritdoc}
     */
    public function current() {
        return $this->current;
    }


    /**
     * {@inheritdoc}
     */
    public function valid() {
        return $this->total > 0 && $this->page < $this->total;
    }


    /**
     * {@inheritdoc}
     */
    public function next() {
        $this->page += 1;

        if ($this->valid() || ($this->total == -1)) {
            $this->data  = $this->getResult([
                'page' => ($this->page + 1)
            ]);

            $this->total = $this->data->total;
        }

        $email = (isset($this->data->chats[$this->key()]->visitor->email))? $this->data->chats[$this->key()]->visitor->email: '';
        $agent_name = (isset($this->data->chats[$this->key()]->agents->display_name))? $this->data->chats[$this->key()]->agents->display_name: '';
        $agent_email = (isset($this->data->chats[$this->key()]->agents->display_email))? $this->data->chats[$this->key()]->agents->display_email: '';

        $this->current = [
            'type'              => $this->data->chats[$this->key()]->type,
            'id'                => $this->data->chats[$this->key()]->id,
            'visitor_name'      => $this->data->chats[$this->key()]->visitor->name,
            'visitor_id'        => $this->data->chats[$this->key()]->visitor->id,
            'visitor_ip'        => $this->data->chats[$this->key()]->visitor->ip,
            'visitor_email'     => $email,
            'visitor_city'      => $this->data->chats[$this->key()]->visitor->city,
            'visitor_country'   => $this->data->chats[$this->key()]->visitor->country,
            'visitor_country_code' => $this->data->chats[$this->key()]->visitor->country_code,
            'timezone'          => $this->data->chats[$this->key()]->timezone,
            'agents_display_name' => $agent_name,
            'agents_email'      => $agent_email,
            'duration'          => $this->data->chats[$this->key()]->duration,
            'started'           => $this->data->chats[$this->key()]->started,
            'started_timezone'  => $this->data->chats[$this->key()]->started_timestamp,
            'ended_timezone'    => $this->data->chats[$this->key()]->ended_timestamp,
            'ended'             => $this->data->chats[$this->key()]->ended,
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function key() {
        return $this->page;
    }


    /**
     * @param array $params
     *
     * @return array
     */
    abstract protected function getResult($params);
}

