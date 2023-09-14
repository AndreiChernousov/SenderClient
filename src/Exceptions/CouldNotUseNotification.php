<?php

namespace NotificationChannels\SenderClient\Exceptions;

class CouldNotUseNotification extends \Exception
{

    public static function missingMethod(): static
    {
        return new static(
          'Your notification does not have the toSender method. Please create.'
        );
    }

}
