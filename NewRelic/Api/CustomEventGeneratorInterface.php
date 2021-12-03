<?php
/**
 * FileDoc
 */

namespace Graycore\NewRelicGraphQl\NewRelic\Api;

/**
 * Inteface for services that generate New Relic custom events
 */
interface CustomEventGeneratorInterface
{
    /**
     * Record new relic custom event given its name and its data
     *
     * @param string $eventName
     * @param array $eventData
     * @return void
     */
    public function recordCustomEvent(string $eventName, array $eventData);
}
