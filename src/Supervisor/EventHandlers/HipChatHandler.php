<?php namespace Shift31\Supervisor\EventHandlers;

use Buzz\Browser;
use Buzz\Client\Curl;
use GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI;
use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use GorkaLaucirica\HipchatAPIv2Client\Client;
use GorkaLaucirica\HipchatAPIv2Client\Model\Message;
use Mtdowling\Supervisor\EventNotification;


/**
 * Class HipChatHandler
 *
 * @package Shift31\Supervisor\EventHandlers
 */
class HipChatHandler extends EventHandler
{
    /**
     * @var \stdClass
     */
    private $config;

    /**
     * @var RoomAPI
     */
    private $roomApi;


    /**
     * @param \stdClass $config
     */
    public function __construct($config)
    {
        parent::__construct();
        $this->config = $config;

        $auth = new OAuth2($config->authToken);
        $curlBrowser = new Curl();
        $curlBrowser->setTimeout(10);
        $client = new Client($auth, new Browser($curlBrowser));
        $this->roomApi = new RoomAPI($client);
    }


    /**
     * @param EventNotification $event
     *
     * @return void
     */
    public function handle(EventNotification $event)
    {
        $message = new Message();
        $message->setNotify(true);
        $message->setColor($this->getColor($event->getEventName()));
        $message->setMessage($this->formatMessage($event));

        $this->roomApi->sendRoomNotification($this->config->room, $message);
    }


    /**
     * @param string $eventName
     *
     * @return string
     */
    private function getColor($eventName)
    {
        switch (true) {
            case str_contains($eventName, 'FATAL'):
                return Message::COLOR_RED;
                break;
            case ($eventName == 'PROCESS_STATE_RUNNING'):
                return Message::COLOR_GREEN;
                break;
            case ($eventName == 'PROCESS_STATE_BACKOFF'):
                return Message::COLOR_YELLOW;
                break;
            default:
                return Message::COLOR_GRAY;
                break;
        }
    }
}