<?php

namespace Graycore\NewRelicGraphQl\NewRelic;

use Graycore\NewRelicGraphQl\NewRelic\Api\CustomEventGeneratorInterface;

class CustomEventGenerator implements CustomEventGeneratorInterface
{
    /**
     * @var \Magento\NewRelicReporting\Model\NewRelicWrapper
     */
    private $newRelicWrapper;

    /**
     * CustomEventGenerator constructor
     *
     * @param \Magento\NewRelicReporting\Model\NewRelicWrapper $newRelicWrapper
     */
    public function __construct(
        \Magento\NewRelicReporting\Model\NewRelicWrapper $newRelicWrapper
    ) {
        $this->newRelicWrapper = $newRelicWrapper;
    }

    /**
     * Concrete implementation of record custom event.
     * Will only try to record events if new relic agent is installed
     *
     * @param string $eventName
     * @param array $eventData
     * @return void
     */
    public function recordCustomEvent(string $eventName, array $eventData)
    {
        if ($this->newRelicWrapper->isExtensionInstalled()) {
            newrelic_record_custom_event($eventName, $eventData);
        }
    }
}
