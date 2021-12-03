<?php

namespace Graycore\NewRelicGraphQl\Test\Unit\Plugin;

use Graycore\NewRelicGraphQl\NewRelic\Api\CustomEventGeneratorInterface;
use Graycore\NewRelicGraphQl\GraphQl\OperationHelper;
use Graycore\NewRelicGraphQl\Plugin\CustomEventProcessor;
use JomaShop\NewRelicMonitoring\Helper\NewRelicReportData;
use Magento\Framework\GraphQl\Query\QueryProcessor;
use Magento\Framework\GraphQl\Schema;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CustomEventProcessorTest extends TestCase
{

    /**
     * @var CustomEventGeneratorInterface|MockObject
     */
    private $customEventGeneratorMock;

    /**
     * @var OperationHelper|MockObject
     */
    private $operationHelperMock;

    /**
     * @var NewRelicReportData|MockObject
     */
    private $graphQlDataHelperMock;

    /**
     * Tested class
     *
     * @var CustomEventProcessor;
     */
    private $customEventProcessor;

    protected function setUp(): void
    {
        $this->customEventGeneratorMock = $this->createMock(CustomEventGeneratorInterface::class);
        $this->operationHelperMock = $this->createMock(OperationHelper::class);
        $this->graphQlDataHelperMock = $this->createMock(NewRelicReportData::class);

        $this->customEventProcessor = new CustomEventProcessor(
            $this->customEventGeneratorMock,
            $this->operationHelperMock,
            $this->graphQlDataHelperMock
        );
    }

    public function testBeforeProcessGenerateEventForEachBaseNode()
    {
        $schema = $this->createMock(Schema::class);
        $source = 'source';
        $graphQlData = [
            'transactionName' => 'tName',
            'fieldNames' => ['node1', 'node2']
        ];
        $operationName = 'operationName';

        $expectedEvent1 = [
            'operation' => $operationName,
            'transactionName' => $graphQlData['transactionName'],
            'nodeName' => 'node1'
        ];

        $expectedEvent2 = [
            'operation' => $operationName,
            'transactionName' => $graphQlData['transactionName'],
            'nodeName' => 'node2'
        ];

        $this->graphQlDataHelperMock
            ->expects($this->once())
            ->method('getTransactionData')
            ->with($this->equalTo($schema), $this->equalTo($source))
            ->willReturn($graphQlData);

        $this->operationHelperMock
            ->expects($this->exactly(count($graphQlData['fieldNames'])))
            ->method('getGraphQlOperation')
            ->with($this->equalTo($schema))
            ->willReturn($operationName);

        $this->customEventGeneratorMock
            ->expects($this->exactly(count($graphQlData['fieldNames'])))
            ->method('recordCustomEvent')
            ->withConsecutive(
                [$this->equalTo(CustomEventProcessor::GRAPH_QL_EVENT_NAME), $this->equalTo($expectedEvent1)],
                [$this->equalTo(CustomEventProcessor::GRAPH_QL_EVENT_NAME), $this->equalTo($expectedEvent2)]
            );
        
        $response = $this->customEventProcessor->beforeProcess(
            $this->createMock(QueryProcessor::class),
            $schema,
            $source
        );

        $this->assertNull($response);
    }

    public function testBeforeProcessDoNothingWhenEmptyListOfNodes()
    {
        $schema = $this->createMock(Schema::class);
        $source = 'source';
        $graphQlData = [
            'transactionName' => 'tName',
            'fieldNames' => []
        ];

        $this->graphQlDataHelperMock
            ->expects($this->once())
            ->method('getTransactionData')
            ->with($this->equalTo($schema), $this->equalTo($source))
            ->willReturn($graphQlData);
        
        $this->operationHelperMock
            ->expects($this->never())
            ->method('getGraphQlOperation');

        $this->customEventGeneratorMock
            ->expects($this->never())
            ->method('recordCustomEvent');

        $response = $this->customEventProcessor->beforeProcess(
            $this->createMock(QueryProcessor::class),
            $schema,
            $source
        );
    
        $this->assertNull($response);
    }
}
