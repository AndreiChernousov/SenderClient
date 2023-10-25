## SenderClient
This Laravel package is designed for sending notifications to a gateway for subsequent processing and delivery.

#### Install

- composer config repositories.repo-name vcs https://github.com/AndreiChernousov/SenderClient.git
- composer require andc/senderclient:dev-main
- php artisan vendor:publish --provider='NotificationChannels\SenderClient\SenderClientServiceProvider'
