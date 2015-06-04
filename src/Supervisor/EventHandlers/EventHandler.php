<?php namespace Shift31\Supervisor\EventHandlers;

use Mtdowling\Supervisor\EventNotification;
use Shift31\Supervisor\HandlesEvents;


/**
 * Class EventHandler
 *
 * @package Shift31\Supervisor\EventHandlers
 */
abstract class EventHandler implements HandlesEvents
{
    /**
     * @var string
     */
    protected static $fqdn;


    public function __construct()
    {
        self::$fqdn = `hostname -f`;
    }


    /**
     * @param EventNotification $event
     *
     * @return string
     */
    protected function formatMessage(EventNotification $event)
    {
        switch (true) {
            case starts_with($event->getEventName(), 'PROCESS_STATE'):
                $message = self::$fqdn .
                    ": {$event->getEventName()} for {$event->getData('groupname')}:{$event->getData('processname')}";
                break;
            default:
                $message = self::$fqdn . ': ' . print_r($event->getData(), true);
                break;
        }

        return $message;
    }
}