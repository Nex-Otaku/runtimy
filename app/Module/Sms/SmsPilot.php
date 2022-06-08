<?php

namespace App\Module\Sms;

use App\Module\Common\Json;
use App\Module\Common\PhoneNumber;
use App\Module\Sms\Entities\SmspilotResponse;
use App\Module\Sms\Entities\SmsRequest;
use GuzzleHttp\Client;

class SmsPilot implements SmsSender
{
    private Client $client;

    private string $apiKey;

    private const API_URL = 'http://smspilot.ru/api.php';

    private function __construct(
        string $apiKey
    ) {
        if (strlen($apiKey) === 0) {
            throw new \InvalidArgumentException('Не указан ключ для API smspilot.ru');
        }

        $this->apiKey = $apiKey;
        $this->client = new Client();
    }

    public static function makeFromParams(string $apiId): self
    {
        return new self($apiId);
    }

    public static function makeFromConfig(): self
    {
        $apiId = config('smspilot.apiKey');

        return new self($apiId);
    }

    public function sendSms(PhoneNumber $phoneNumber, string $message): void
    {
        $smsRequest = SmsRequest::create($phoneNumber, $message);

        try {
            $response = $this->client->post(self::API_URL, [
                'form_params' => [
                    'apikey' => $this->apiKey,
                    'to' => $phoneNumber->asSmsPilotFormat(),
                    'send' => $message,
                    'format' => 'json',
                ],
            ]);

            $statusCode = $response->getStatusCode();

            if (($statusCode < 200) || ($statusCode >= 300)) {
                $smsRequest->markAsFailed('Ошибка HTTP ' . $statusCode);

                return;
            }

            $contents = $response->getBody()->getContents();
            $json = Json::decode($contents);

            if ($json === null) {
                $smsRequest->markAsFailed('Некорректный ответ JSON от smspilot: ' . $contents);

                return;
            }

            SmspilotResponse::create($smsRequest->getModelId(), $json);
        } catch (\Throwable $throwable) {
            $smsRequest->markAsFailed((string)$throwable);

            return;
        }

        $smsRequest->markAsSent();
    }

    public function sendCode(PhoneNumber $phoneNumber): void
    {
        $this->sendSms($phoneNumber, 'Ваш код 1122');
    }
}
