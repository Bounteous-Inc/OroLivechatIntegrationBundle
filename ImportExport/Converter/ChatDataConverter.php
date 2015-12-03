<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\ImportExport\Converter;

use Oro\Bundle\ImportExportBundle\Converter\AbstractTableDataConverter;

class ChatDataConverter extends AbstractTableDataConverter
{
    /**
     * {@inheritdoc}
     */
    protected function getHeaderConversionRules()
    {
        $rules = [
            'type'                          => 'chatType',
            'id'                            => 'chatId',
            'visitor_name'                  => 'chatVisitorName',
            'visitor_id'                    => 'chatVisitorId',
            'custom_visitor_ip'             => 'chatVisitorIp',
            'custom_visitor_email'          => 'chatVisitorEmail',
            'custom_visitor_region'         => 'chatVisitorRegion',
            'custom_visitor_city'           => 'chatVisitorCity',
            'custom_visitor_country'        => 'chatVisitorCountry',
            'custom_visitor_country_code'   => 'chatVisitorCountryCode',
            'custom_visitor_timezone'       => 'chatVisitorTimezone',
            'custom_agent_name'             => 'chatAgentName',
            'custom_agent_email'            => 'chatAgentEmail',
            'duration'                      => 'chatDuration',
            'started'                       => 'chatStarted',
            'started_timestamp'             => 'chatStartedTimestamp',
            'ended_timestamp'               => 'chatEndedTimestamp',
            'ended'                         => 'chatEnded',
            'chat_start_url'                => 'chatStartUrl'
        ];

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    protected function getBackendHeader()
    {
        return array_values($this->getHeaderConversionRules());
    }

    /**
     * {@inheritDoc}
     */
    public function convertToExportFormat(array $exportedRecord, $skipNullValues = true)
    {
        throw new \Exception('Export not implemented yet.');
    }
}
