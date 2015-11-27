<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Provider\Transport;

use LiveChat\Api\Client;

abstract class AbstractLivechatIterator implements \Iterator
{
    const BATCH_SIZE = 10;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var int
     */
    protected $batchSize;

    /**
     * @var mixed
     */
    protected $current = null;

    /**
     * @var int
     */
    protected $offset = -1;

    /**
     * @var int
     */
    protected $total = -1;

    /**
     * @var array|null
     */
    protected $data;

    /**
     * @param Client $client
     * @param array  $params
     * @param int    $batchSize
     */
    public function __construct(Client $client, $params = [], $batchSize = self::BATCH_SIZE)
    {
        $this->client  = $client;
        $defaultParams = [
            'sort'      => 'updated',
            'direction' => 'asc',
            'per_page'  => self::BATCH_SIZE
        ];
        $this->params    = array_merge($defaultParams, $params);
        $this->batchSize = $batchSize > 0 ? $batchSize : self::BATCH_SIZE;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->total > 0 && $this->offset < $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->offset += 1;
        $key = $this->offset % $this->batchSize;

        if (($this->valid() || ($this->total == -1)) && $key == 0) {
            $this->data  = $this->getResult(
                [
                    'page'     => (int)($this->offset / $this->batchSize) + 1,
                    'per_page' => $this->batchSize
                ]
            );
            $this->total = count($this->data);
        }

        $this->current = isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->current = null;
        $this->offset  = -1;
        $this->total   = -1;
        $this->data    = null;

        $this->next();
    }

    /**
     * @param array $params
     *
     * @return array
     */
    abstract protected function getResult($params);
}