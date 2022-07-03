<?php

namespace App\Module\Sms\Entities;

use App\Module\Sms\Models\SmspilotResponse as SmspilotResponseModel;

class SmspilotResponse
{
    /** @var SmspilotResponseModel */
    private $smspilotResponse;

    private function __construct(
        SmspilotResponseModel $smspilotResponse
    ) {
        $this->smspilotResponse = $smspilotResponse;
    }

    public static function create(int $smsRequestId, array $json): self
    {
        $smspilotResponse = new SmspilotResponseModel(
            [
                'sms_request_id' => $smsRequestId,
                'server_id' => $json['send'][0]['server_id'] ?? 0,
                'phone' => $json['send'][0]['phone'] ?? '',
                'price' => $json['send'][0]['price'] ?? '0.0',
                'status' => $json['send'][0]['status'] ?? 0,
                'balance' => $json['balance'] ?? '0.0',
                'cost' => $json['cost'] ?? '0.0',
                'server_packet_id' => $json['server_packet_id'] ?? 0,
            ]
        );

        $smspilotResponse->saveOrFail();

        return new self($smspilotResponse);
    }
}
