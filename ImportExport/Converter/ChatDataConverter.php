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
        /*
         * Array 'key' must be the name from LivechatInc field
         * Array 'value' must be the name of the field living in Oro "ENTITY" (NOT oro db field name)
         */
        $rules = [
            'type'              => 'chatType',
            'id'                => 'chatId',
            'visitor_name'      => 'chatVisitorName',
            'visitor_id'        => 'chatVisitorId',
            'visitor_ip'        => 'chatVisitorIp',
            'visitor_email'     => 'chatVisitorEmail',
            'visitor_city'      => 'chatVisitorCity',
            'visitor_country'   => 'chatVisitorCountry',
            'visitor_country_code' => 'chatVisitorCountryCode',
            'timezone'          => 'chatVisitorTimezone',
            'agents_display_name' => 'chatAgentName',
            'agents_email'      => 'chatAgentEmail',
            'duration'          => 'chatDuration',
            'started'           => 'chatStarted',
            'started_timezone'  => 'chatStartedTimestamp',
            'ended_timezone'    => 'chatEndedTimestamp',
            'ended'             => 'chatEnded',
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
        throw new \Exception('Export not implemented yet!');
    }
}