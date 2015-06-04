#!/usr/bin/env php
<?php

require __DIR__.'/../src/vendor/autoload.php';

use Mtdowling\Supervisor\EventListener;
use Mtdowling\Supervisor\EventNotification;
use Shift31\Supervisor\EventHandlerFactory;


if (file_exists('/etc/supervisor-event-notifier.json')) {
    $config = json_decode(file_get_contents('/etc/supervisor-event-notifier.json'));
} elseif (file_exists(__DIR__ . '/../supervisor-event-notifier.json')) { // for development/testing
    $config = json_decode(file_get_contents(__DIR__ . '/../supervisor-event-notifier.json'));
} else {
    echo "No config file found! Please create /etc/supervisor-event-notifier.json";
    exit(1);
}

$listener = new EventListener();
$listener->listen(function(EventListener $listener, EventNotification $event) use ($config) {

    if (isset($config->events->{$event->getEventName()}->handlers)) {
        foreach ($config->events->{$event->getEventName()}->handlers as $handlerName) {
            $handlerConfig = $config->handlers->$handlerName;
            $handler = EventHandlerFactory::create($handlerConfig);
            $handler->handle($event);
            $listener->log("{$event->getEventName()} sent to $handlerName");
        }
    }

    return true;
});