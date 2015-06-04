# Supervisor Event Notifier (SEN)

An event listener for Supervisor, which can send event notifications to various systems. HipChat and VictorOps are currently supported.

## System Requirements

- Supervisord
- PHP >= 5.4

## Installation

Download `sen.phar`, copy to `/usr/local/sbin`, set executable permissions, and optionally rename to `sen`.

## Configuration

### Supervisor

Add the following to your `supervisor.conf` or in `/etc/supervisor/conf.d/sen.conf`, customizing as desired:

```
[eventlistener:sen]
command=/usr/local/sbin/sen
process_name=sen
numprocs=1
events=PROCESS_STATE
stdout_logfile=/var/log/supervisor/sen-out.log
stderr_logfile=/var/log/supervisor/sen-err.log
```

### SEN

Create `/etc/supervisor/sen.json`. Here's an example:

```json
{
  "handlers": {
    "HipChatExample": {
      "type": "HipChat",
      "authToken": "YOUR_APIv2_AUTH_TOKEN",
      "room": "ROOM_ID_OR_NAME"
    },
    "VictorOpsExample": {
      "type": "VictorOps",
      "endpointUrl": "ENDPOINT_URL",
      "routingKey": "Supervisor"
    }
  },

  "events": {
    "PROCESS_STATE_FATAL": {
      "handlers": [
        "HipChatExample",
        "VictorOpsExample"
      ]
    },
    "PROCESS_STATE_BACKOFF": {
      "handlers": [
        "HipChatExample"
      ]
    }
  }
}
```