#!/usr/bin/env php
<?php

require __DIR__.'/../src/vendor/autoload.php';

use Mtdowling\Supervisor\EventListener;
use Mtdowling\Supervisor\EventNotification;
use Shift31\Supervisor\EventHandlerFactory;


if (file_exists('/etc/supervisor/sen.json')) {
    $config = json_decode(file_get_contents('/etc/supervisor/sen.json'));
} elseif (file_exists(__DIR__ . '/../sen.json')) { // for development/testing
    $config = json_decode(file_get_contents(__DIR__ . '/../sen.json'));
} else {
    echo "No config file found! Please create /etc/supervisor/sen.json";
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