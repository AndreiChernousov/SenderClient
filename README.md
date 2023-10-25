# SenderClient
This Laravel package is designed for sending notifications to a gateway for subsequent processing and delivery.

## Install

- composer config repositories.repo-name vcs https://github.com/AndreiChernousov/SenderClient.git
- composer require andc/senderclient:dev-main
- php artisan vendor:publish --provider='NotificationChannels\SenderClient\SenderClientServiceProvider'

## Server API
HOST: https://sender-host

### Authorization
POST  /api/v1/user/auth

request:

```json
{
    "email": "someemail@email.com", // string email
    "password": "somepassword", // string
    "device_name": "some-device-name" // string
}
```

response (json):

```json
{
    "status": STATUS, // status code described below
    "response_code": RESPONSE_CODE, // response code described below
    "message": "MESSAGE", // string
    "data": {
        "token": "token_string", // string
        "expires_at": "2021-01-01 00:00:00"
    }
}
```

### Send notification
POST /api/v1/message/send

headers:
- Authorization: Bearer token_string
- Accept: application/json

request:
```json
{
    "recipient_id": 'some-recipient-id-string', // string
    "message": "message text" // string max 4096 symbols
}
```

response (json):
```json
{
    "status": STATUS, // status code described below
    "response_code": RESPONSE_CODE, // response code described below
    "message": "MESSAGE"
}
```

STATUS codes:
- 200 - request successfully completed
- 401 - authorization problem/sender problem
- 406 - data validation problem
- 404 - URL not found

RESPONSE_CODE codes:
- 1000 - added to queue
- 1100 - successful authorization
- 2000 - wrong login/password
- 2010 - wrong token
- 2020 - recipient not found
- 2030 - URL not found
- 2040 - validation error of incoming data
- 3000 - unknown error (@todo)


