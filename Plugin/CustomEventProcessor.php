<?php

/**
 * Copyright © Graycore, LLC. All rights reserved.
 * See LICENSE.md for details.
 */

namespace Graycore\NewRelicGraphQl\Plugin;

use Magento\Framework\GraphQl\Schema;
use Magento\Framework\GraphQl\Query\QueryProcessor;

class CustomEventProcessor
{
    const GRAPH_QL_EVENT_NAME='GraphQlTransaction';

    /**
     * @var \Graycore\NewRelicGraphQl\NewRelic\Api\CustomEventGeneratorInterface
     */
    private $customEventGenerator;

    /**
     * @var \Graycore\NewRelicGraphQl\GraphQl\OperationHelper
     */
    private $operationHelper;

    /**
     * @var \JomaShop\NewRelicMonitoring\Helper\NewRelicReportData
     */
    private $graphQlDataHelper;

    /**
     * CustomEventProcessor constructor
     *
     * @param \Graycore\NewRelicGraphQl\NewRelic\Api\CustomEventGeneratorInterface $customEventGenerator
     * @param \JomaShop\NewRelicMonitoring\Helper\NewRelicReportData $graphQlDataHelper
     * @return void
     */
    public function __construct(
        \Graycore\NewRelicGraphQl\NewRelic\Api\CustomEventGeneratorInterface $customEventGenerator,
        \Graycore\NewRelicGraphQl\GraphQl\OperationHelper $operationHelper,
        \JomaShop\NewRelicMonitoring\Helper\NewRelicReportData $graphQlDataHelper
    ) {
        $this->customEventGenerator = $customEventGenerator;
        $this->graphQlDataHelper = $graphQlDataHelper;
        $this->operationHelper = $operationHelper;
    }

    /**
     * Create new relic custom event for each base node on a GraphQl transaction
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param QueryProcessor $subject
     * @param Schema $schema
     * @param string $source
     */
    public function beforeProcess(
        QueryProcessor $subject,
        Schema $schema,
        string $source
    ) {
        $graphQlData = $this->graphQlDataHelper->getTransactionData($schema, $source);

        foreach ($graphQlData['fieldNames'] as $nodeName) {

            $customEventData = [
                'operation' => $this->operationHelper->getGraphQlOperation($schema),
                'transactionName' => $graphQlData['transactionName'],
                'nodeName' => $nodeName
            ];

            $this->customEventGenerator->recordCustomEvent(self::GRAPH_QL_EVENT_NAME, $customEventData);
        }
            
        return null;
    }
}
