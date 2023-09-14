<?php

namespace NotificationChannels\SenderClient;

use Illuminate\Support\ServiceProvider;

class SenderClientServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/sender_client.php' => config_path(
                'sender_client.php'
            ),
        ]);
    }

}
