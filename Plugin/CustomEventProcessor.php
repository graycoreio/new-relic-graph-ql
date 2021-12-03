<?php
/**
 * FileDoc
 */
namespace Graycore\NewRelicGraphQl\Plugin;

use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema;
use Magento\Framework\GraphQl\Query\QueryProcessor;

/**
 * Undocumented class
 */
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
     * @param QueryProcessor $subject
     * @param Schema $schema
     * @param string $source
     * @param ContextInterface|null $contextValue
     * @param array|null $variableValues
     * @param string|null $operationName
     */
    public function beforeProcess(
        QueryProcessor $subject,
        Schema $schema,
        string $source,
        ContextInterface $contextValue = null,
        array $variableValues = null,
        string $operationName = null
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
