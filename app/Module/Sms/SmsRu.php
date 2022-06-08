<?php

namespace App\Module\Sms;

use App\Module\Common\PhoneNumber;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SmsRu implements SmsSender
{
    private Client $client;

    private string $apiId;

    private const API_URL = 'https://sms.ru/sms/send';

    private function __construct(
        string $apiId
    )
    {
        if (strlen($apiId) === 0) {
            throw new \InvalidArgumentException('Не указан ключ для API Sms.Ru');
        }

        $this->apiId = $apiId;
        $this->client = new Client();
    }

    public static function makeFromParams(string $apiId): self
    {
        return new self($apiId);
    }

    public static function makeFromConfig(): self
    {
        $apiId = config('smsru.apiId');

        return new self($apiId);
    }

    public function sendSms(PhoneNumber $phoneNumber, string $message): void
    {
        $number = $phoneNumber->asSmsRuFormat();

        Log::info("SmsRu::send. {$number}, {$message}");

        $request = $this->client->post(self::API_URL, [
            'form_params' => [
                'api_id' => $this->apiId,
                'to' => $number,
                'text' => $message,
                'json' => 1,
            ],
        ]);

        $json = $request->getBody()->getContents();

        var_dump($json);

        Log::info("SmsRu::send. response: " . $json);

        $body1 = json_decode($request->getBody()->getContents());
        $body2 = json_decode($request->getBody()->getContents(), true);

        Log::info("SmsRu::send. status foo $body1, {$body1?->status}");
        Log::info("SmsRu::send. status bar $body2, " . ($body2['status'] ?? ''));

        /*if($body->status === 'OK') {
            Log::info("SmsRu::send. result - success {$number}");
        } else {
            Log::info("SmsRu::send. result - failed {$number}");
        }*/
    }

    public function getBalance(): float
    {
        $balance = $this->client->post('https://sms.ru/my/balance', [
            'form_params' => [
                'api_id' => $this->apiId,
            ],
        ]);

        return Str::of($balance->getBody()->getContents())->explode("\n")[1];
    }
}
