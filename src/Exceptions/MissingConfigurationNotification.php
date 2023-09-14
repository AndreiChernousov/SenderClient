<?php

namespace NotificationChannels\SenderClient\Exceptions;

class MissingConfigurationNotification extends \Exception
{

    public static function missingConfig(): static
    {
        return new static(
            'SenderClient endpoint and / or login and / or password are missing. Did you add it to service array and check your .env file?'
        );
    }

}
