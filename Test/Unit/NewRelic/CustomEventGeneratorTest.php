<?php

namespace Graycore\NewRelicGraphQl\Test\Unit\NewRelic;

use Graycore\NewRelicGraphQl\NewRelic\CustomEventGenerator;
use Magento\NewRelicReporting\Model\NewRelicWrapper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CustomEventGeneratorTest extends TestCase
{
    /**
     * @var NewRelicWrapper|MockObject
     */
    private $newRelicWrapperMock;

    /**
     * Tested class
     *
     * @var CustomEventGenerator
     */
    private $customEventGenerator;

    protected function setUp(): void
    {
        $this->newRelicWrapperMock = $this->createMock(NewRelicWrapper::class);

        $this->customEventGenerator = new CustomEventGenerator($this->newRelicWrapperMock);
    }

    public function testRecordCustomEventChecksForNewRelicExtensionInstalled()
    {
        $eventName = 'test';
        $eventData = ['data' => 'data'];

        $this->newRelicWrapperMock->expects($this->once())
            ->method('isExtensionInstalled')
            ->will($this->returnValue(true));
        
        $this->customEventGenerator->recordCustomEvent($eventName, $eventData);
    }
}
