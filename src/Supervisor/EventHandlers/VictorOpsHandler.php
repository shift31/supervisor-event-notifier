<?php namespace Shift31\Supervisor\EventHandlers;

use Mtdowling\Supervisor\EventNotification;
use Signiant\VictorOps\Messages\CustomMessage;
use Signiant\VictorOps\Notifier;


/**
 * Class VictorOpsHandler
 *
 * @package Shift31\Supervisor\EventHandlers
 */
class VictorOpsHandler extends EventHandler
{
    /**
     * @var Notifier
     */
    private $notifier;


    /**
     * @param \stdClass $config
     */
    public function __construct($config)
    {
        parent::__construct();

        $this->notifier = new Notifier([
            'endpoint_url' => $config->endpointUrl,
            'routing_key' => $config->routingKey
        ]);
    }


    /**
     * @param EventNotification $event
     *
     * @return void
     */
    public function handle(EventNotification $event)
    {
        $message = new CustomMessage($this->getMessageType($event->getEventName()));
        $message->entityId($this->getEntityId($event));
        $message->stateMessage($this->formatMessage($event));

        $this->notifier->send($message);
    }


    /**
     * @param string $eventName
     *
     * @return string
     */
    private function getMessageType($eventName)
    {
        switch (true) {
            case str_contains($eventName, 'FATAL'):
                return 'CRITICAL';
                break;
            case ($eventName == 'PROCESS_STATE_BACKOFF'):
                return 'WARNING';
                break;
            default:
                return 'INFO';
                break;
        }
    }


    /**
     * @param EventNotification $event
     *
     * @return string
     */
    private function getEntityId(EventNotification $event)
    {
        switch (true) {
            case starts_with($event->getEventName(), 'PROCESS_STATE'):
                return "{$event->getData('groupname')}:{$event->getData('processname')}/" . self::$fqdn;
                break;
            default:
                return self::$fqdn;
                break;
        }
    }
}