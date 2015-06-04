<?php namespace Shift31\Supervisor;

use Shift31\Supervisor\EventHandlers\HipChatHandler;


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
                break;
            default:
                throw new \Exception("No EventHandler for '$config->type'");
                break;
        }
    }
}