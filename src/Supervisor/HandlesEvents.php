<?php namespace Shift31\Supervisor;

use Mtdowling\Supervisor\EventNotification;


/**
 * Interface HandlesEvents
 *
 * @package Shift31\Supervisor
 */
interface HandlesEvents
{
    /**
     * @param EventNotification $event
     *
     * @return void
     */
    public function handle(EventNotification $event);
}