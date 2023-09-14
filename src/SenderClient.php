<?php

namespace NotificationChannels\SenderClient;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use NotificationChannels\SenderClient\Exceptions\CouldNotSendNotification;
use NotificationChannels\SenderClient\Exceptions\MissingConfigurationNotification;

class SenderClient
{

    // const endpoint = 'http://127.0.0.1:8000/api/v1';

    // const email = 'testserver@server.com';

    // const password = 'sew23we7ysadqwenuygasb7qe6bd865asfqwedastfbq23481c';

    protected string $deviceName = '';

    protected string $token = '';

    protected string $recipient = '';

    protected string $endpoint = '';

    protected string $login = '';

    protected string $password = '';

    /**
     * @throws \NotificationChannels\SenderClient\Exceptions\MissingConfigurationNotification
     */
    public function __construct(
        protected readonly string $message,
        protected readonly string $type,
    ) {
        $this->deviceName = env('APP_NAME', 'default');
        $this->endpoint = config('sender_client.endpoint');
        $this->login = config('sender_client.login');
        $this->password = config('sender_client.password');

        if (!$this->endpoint || !$this->login || !$this->password) {
            throw MissingConfigurationNotification::missingConfig();
        }
    }

    /**
     * @throws \NotificationChannels\SenderClient\Exceptions\CouldNotSendNotification
     */
    public function send($secondTry = false): self
    {
        $this->token = $this->getToken();
        $response = $this->sendRequest('/message/send', [
            'recipient' => $this->recipient,
            'message' => $this->message,
            'type' => $this->type,
        ]);

        if ($response['status'] == 200) {
            return $this;
        }

        if ($response['response_code'] == 2010 && $secondTry) { // token error
            throw CouldNotSendNotification::serviceRespondedWithAnError(
                $response['message'],
                $response['response_code']
            );
        }
        if ($response['status'] == 401 && !$secondTry) {
            return $this->tryAuthAndSend();
        }
    }

    /**
     * @throws \NotificationChannels\SenderClient\Exceptions\CouldNotSendNotification
     */
    protected function tryAuthAndSend(): self
    {
        $this->token = $this->getToken(false);
        return $this->send(true);
    }

    /**
     * @throws \Exception
     */
    protected function sendRequest(
        string $url,
        array $params,
        bool $secondTry = false
    ): array {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $request = Http::withHeaders($headers);
        if (!empty($this->token)) {
            $request->withToken($this->token);
        }
        $response = $request->post($this->endpoint . $url, $params)->json();
        $this->validateResponse($response);

        return $response;

        // $response status - 401 - CREDENTIALS_ERROR
        // $response status - 401 - CREDENTIALS_ERROR
    }

    /**
     * @throws \NotificationChannels\SenderClient\Exceptions\CouldNotSendNotification
     */
    protected function validateResponse(array $response): void
    {
        if (in_array($response['response_code'], ['2000', '2020'])) {
            throw CouldNotSendNotification::serviceRespondedWithAnError(
                $response['message'],
                $response['response_code']
            );
        }
    }

    protected function getToken($useCache = true): string
    {
        if (!Cache::has('SenderClientToken') || !$useCache) {
            $response = $this->sendRequest('/user/auth', [
                'email' => $this->login,
                'password' => $this->password,
                'device_name' => $this->deviceName,
            ]);
            $token = $response['data'];

            Cache::put('SenderClientToken', $token, now()->addDays(5));
        } else {
            $token = Cache::get('SenderClientToken');
        }

        return $token;
    }

    public function to($to): self
    {
        $this->recipient = $to;
        return $this;
    }

}
