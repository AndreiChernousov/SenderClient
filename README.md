## SenderClient
Client for sending messages to the server.

#### Install

- composer config repositories.repo-name vcs https://github.com/AndreiChernousov/SenderClient.git
- composer require andc/senderclient:dev-main
- php artisan vendor:publish --provider='NotificationChannels\SenderClient\SenderClientServiceProvider'
