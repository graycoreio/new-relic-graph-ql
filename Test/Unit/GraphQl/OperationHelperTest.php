<?php

namespace Graycore\NewRelicGraphQl\Test\Unit\GraphQl;

use Magento\Framework\GraphQl\Schema;
use GraphQL\Type\SchemaConfig;
use GraphQL\Type\Definition\ObjectType;
use Graycore\NewRelicGraphQl\GraphQl\OperationHelper;
use PHPUnit\Framework\TestCase;

class OperationHelperTest extends TestCase
{

    /**
     * Tested class
     *
     * @var Graycore\NewRelicGraphQl\GraphQl\OperationHelper
     */
    private $operationHelper;

    protected function setUp(): void
    {
        $this->operationHelper = new OperationHelper();
    }

    public function testGetGraphQlOperationWhenNullSchema()
    {
        $this->assertNull($this->operationHelper->getGraphQlOperation(null));
    }

    public function testGetGraphQlOperationWhenNullSchemaConfig() 
    {
        $schemaMock = $this->createMock(Schema::class);
        $schemaMock->method('getConfig')->willReturn(null);

        $this->assertNull($this->operationHelper->getGraphQlOperation($schemaMock));
    }

    public function testGetGraphQlOperationWhenSchemaHasMutation()
    {
        $schemaMock = $this->createMock(Schema::class);
        $schemaConfigMock = $this->createMock(SchemaConfig::class);
        $mutationMock = $this->createMock(ObjectType::class);
        $expectedName = "MutationMockName";

        $schemaMock->method('getConfig')->willReturn($schemaConfigMock);
        $schemaConfigMock->method('getMutation')->willReturn($mutationMock);
        $mutationMock->method('getFields')->willReturn(['mockField1', 'mockField2']);
        $mutationMock->name = $expectedName;

        $response = $this->operationHelper->getGraphQlOperation($schemaMock);

        $this->assertEquals($expectedName, $response);
    }

    public function testGetGraphQlOperationWhenSchemaHasNoMutation()
    {
        $schemaMock = $this->createMock(Schema::class);
        $schemaConfigMock = $this->createMock(SchemaConfig::class);
        $mutationMock = $this->createMock(ObjectType::class);
        $queryMock = $this->createMock(ObjectType::class);
        $expectedName = "QueryMockName";

        $schemaMock->method('getConfig')->willReturn($schemaConfigMock);
        $schemaConfigMock->method('getMutation')->willReturn($mutationMock);
        $schemaConfigMock->method('getQuery')->willReturn($queryMock);
        $mutationMock->method('getFields')->willReturn([]);
        $queryMock->name = $expectedName;

        $response = $this->operationHelper->getGraphQlOperation($schemaMock);

        $this->assertEquals($expectedName, $response);
    }
}
