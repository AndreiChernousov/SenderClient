<?php

namespace NotificationChannels\SenderClient\Exceptions;

class CouldNotSendNotification extends \Exception
{

    public static function serviceRespondedWithAnError($message, $code): static
    {
        return new static(
            'Sender responded with an error: ' . $message . ' ' . $code
        );
    }

}
