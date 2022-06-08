<?php

namespace App\Module\Sms;

use App\Module\Common\Json;
use App\Module\Common\PhoneNumber;
use GuzzleHttp\Client;

class SmsPilot implements SmsSender
{
    private Client $client;

    private string $apiKey;

    private const API_URL = 'http://smspilot.ru/api.php';

    private function __construct(
        string $apiKey
    )
    {
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
        $number = $phoneNumber->asSmsPilotFormat();

        //send=test&to=79102851138&format=json&apikey=2QVQ3724H38H2P48234CLM15A6213R80M99EOC3PDW052Z45H8UW202O4ZDKNEZ7
        // {"send":[{"server_id":"189940538","phone":"79525422512","price":"3.38","status":"0"}],"balance":"7.00","cost":"3.38", "server_packet_id": "189940538"}
        $request = $this->client->post(self::API_URL, [
            'form_params' => [
                'apikey' => $this->apiKey,
                'to' => $number,
                'send' => $message,
                'format' => 'json',
            ],
        ]);

        $json = Json::decode($request->getBody()->getContents());

        var_dump($json);

//
//        $body1 = json_decode($request->getBody()->getContents());
//        $body2 = json_decode($request->getBody()->getContents(), true);
    }

    public function sendCode(PhoneNumber $phoneNumber): void
    {
        $this->sendSms($phoneNumber, 'Ваш код 1122');
    }
}
