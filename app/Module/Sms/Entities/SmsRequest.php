<?php

namespace App\Module\Sms\Entities;

use App\Models\SmsRequest as SmsRequestModel;
use App\Module\Common\PhoneNumber;

class SmsRequest
{
    private const STATUS_DRAFT     = 'draft';
    private const STATUS_SENT      = 'sent';
    private const STATUS_DELIVERED = 'delivered';
    private const STATUS_FAILED    = 'failed';

    private $smsRequest;

    private function __construct(
        SmsRequestModel $smsRequest
    ) {
        $this->smsRequest = $smsRequest;
    }

    public static function create(PhoneNumber $phoneNumber, string $message): self
    {
        $smsRequest = new SmsRequestModel([
                                              'message' => $message,
                                              'phone_number' => $phoneNumber->asDbValue(),
                                              'status' => self::STATUS_DRAFT,
                                              'error' => '',
                                          ]);

        $smsRequest->saveOrFail();

        return new self($smsRequest);
    }

    public function markAsSent(): void
    {
        $this->setStatus(self::STATUS_SENT);
    }

    public function markAsFailed(string $error): void
    {
        $this->setStatus(self::STATUS_FAILED);
        $this->smsRequest->error = $error;
        $this->smsRequest->saveOrFail();
    }

    private function setStatus(string $status): void
    {
        $this->smsRequest->status = $status;
        $this->smsRequest->saveOrFail();
    }

    public function getModelId(): int
    {
        return $this->smsRequest->id;
    }
}
