<?php namespace Shift31\Supervisor;

use Shift31\Supervisor\EventHandlers\HipChatHandler;
use Shift31\Supervisor\EventHandlers\VictorOpsHandler;


/**
 * Class EventHandlerFactory
 *
 * @package Shift31\Supervisor
 */
class EventHandlerFactory
{
    /**
     * @param \stdClass $config
     *
     * @return HandlesEvents
     * @throws \Exception
     */
    public static function create($config)
    {
        switch ($config->type) {
            case 'HipChat':
                return new HipChatHandler($config);
                break;
            case 'VictorOps':
                return new VictorOpsHandler($config);
                break;
            default:
                throw new \Exception("No EventHandler for '$config->type'");
                break;
        }
    }
}