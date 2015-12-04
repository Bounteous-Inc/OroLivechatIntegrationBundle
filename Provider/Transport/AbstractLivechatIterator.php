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
     * @var bool
     */
    protected $firstLoaded = false;

    /**
     * Results of page data
     *
     * @var array
     */
    protected $rows = array();

    /**
     * Total count of items in response
     *
     * @var int
     */
    protected $totalCount = null;

    /**
     * Offset of current item in current page
     *
     * @var int
     */
    protected $offset = -1;

    /**
     * A position of a current item within the current page
     *
     * @var int
     */
    protected $position = -1;

    /**
     * Current item, populated from request response
     *
     * @var mixed
     */

    protected $params = 0;

    protected $current = null;

    protected $totalPages = 0;

    protected $totalChats = 0;

    protected $currentPage = 388;

    protected $currentChat = 0;

    /**
     * @param Client $client
     */
    public function __construct(Client $client){
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $current = $this->current;

        return $current;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->offset++;

        if (!isset($this->rows[$this->offset]) && !$this->loadNextPage()) {
            $this->current = null;
        } else {
            $this->current  = $this->rows[$this->offset];
        }
        $this->position++;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        if (!$this->firstLoaded) {
            $this->rewind();
        }

        $return = (null !== $this->current);

        if (!$return) {
            // Finished!

        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->firstLoaded  = false;
        $this->totalCount   = null;
        $this->offset       = -1;
        $this->position     = -1;
        $this->current      = null;
        $this->rows         = array();

        $this->next();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if (!$this->firstLoaded) {
            $this->rewind();
        }

        return $this->totalCount;
    }

    /**
     * Attempts to load next page
     *
     * @return bool If page loaded successfully
     */
    protected function loadNextPage()
    {
        $this->rows = array();
        $this->offset = null;
        $this->currentPage++;

        $this->params = [
            'page' => $this->currentPage
        ];

        $pageData = $this->loadPage($this->client, $this->params);

        $this->totalPages = $pageData->pages;
        $this->totalChats = $pageData->total;

        echo PHP_EOL . "Loading page=[{$this->currentPage} of {$this->totalPages}] " .PHP_EOL;

        $this->firstLoaded = true;
        if ($pageData) {

            $this->rows = $this->getRowsFromPageData($pageData->chats);
            $this->totalCount = $this->getTotalCountFromPageData($pageData->chats, $this->totalCount);
            if (null == $this->totalCount && is_array($this->rows)) {
                $this->totalCount = count($this->rows);
            }
            $this->offset = 0;
        }

        $return = count($this->rows) > 0 && $this->totalCount;

        return $return;
    }

    protected function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Load page
     *
     * @param Client $client
     * @return array|null
     */
    abstract protected function loadPage(Client $client, $params);

    /**
     * Get rows from page data
     *
     * @param array $data
     * @return array|null
     */
    abstract protected function getRowsFromPageData($data);

    /**
     * Get total count from page data
     *
     * @param array $data
     * @param integer $previousValue
     * @return array|null
     */
    abstract protected function getTotalCountFromPageData(array $data, $previousValue);

}
