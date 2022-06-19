<?php

namespace App\Module\Payment;

use App\Module\Common\Money;
use App\Module\Payment\Contracts\PaymentOrder;
use App\Module\Payment\Entities\FakeOrder;
use App\Module\Payment\Entities\Payment;
use App\Module\Payment\Entities\YookassaPayment;
use YooKassa\Client;
use YooKassa\Model\PaymentStatus;
use YooKassa\Request\Payments\CreatePaymentResponse;

class YookassaApi
{
    private const DEFAULT_CURRENCY = 'RUB';

    /** @var Client */
    private $client;

    private function __construct()
    {
        // Обращаемся только через ::instance()
        $this->client = new Client();
        $this->client->setAuth($this->getShopId(), $this->getSecretKey());
    }

    public static function instance(): self
    {
        return new self();
    }

    public function createCardPayment(PaymentOrder $paymentOrder, string $returnUrl): Payment
    {
        $payment = Payment::create($paymentOrder);

        try {
            /** @var CreatePaymentResponse $paymentResponse */
            $paymentResponse = $this->client->createPayment(
                [
                    'amount' => [
                        'value' => $payment->getAmount()->toString(),
                        'currency' => self::DEFAULT_CURRENCY,
                    ],
                    'confirmation' => [
                        'type' => 'redirect',
                        'return_url' => $returnUrl,
                    ],
                    'capture' => true,
                    'description' => $payment->getDescription(),
                    'metadata' => [
                        'runtimy_payment_id' => $payment->getModelId(),
                    ],
                ],
                uniqid('', true)
            );
        } catch (\Throwable $throwable) {
            $payment->fail((string)$throwable);

            throw $throwable;
        }

        YookassaPayment::create(
            (int)$this->getShopId(),
            $payment->getModelId(),
            $paymentResponse,
            $returnUrl
        );

        return $payment;
    }

    public function createSbpPayment(PaymentOrder $paymentOrder): Payment
    {
        $payment = Payment::create($paymentOrder);

        try {
            /** @var CreatePaymentResponse $paymentResponse */
            $paymentResponse = $this->client->createPayment(
                [
                    'amount' => [
                        'value' => $payment->getAmount()->toString(),
                        'currency' => self::DEFAULT_CURRENCY,
                    ],
                    'payment_method_data' => [
                        'type' => 'sbp',
                    ],
                    'confirmation' => [
                        'type' => 'qr',
                    ],
                    'capture' => true,
                    'description' => $payment->getDescription(),
                    'metadata' => [
                        'runtimy_payment_id' => $payment->getModelId(),
                    ],
                ],
                uniqid('', true)
            );
        } catch (\Throwable $throwable) {
            $payment->fail((string)$throwable);

            throw $throwable;
        }

        YookassaPayment::create(
            (int)$this->getShopId(),
            $payment->getModelId(),
            $paymentResponse,
            '',
        );

        return $payment;
    }

    public function createTestPayment(): Payment
    {
        return $this->createCardPayment(new FakeOrder(), 'https://www.merchant-website.com/return_url');
    }

    private function getShopId(): string
    {
        $shopId = config('yookassa.shopId');

        if (strlen($shopId) === 0) {
            throw new \LogicException('Не заполнен ID магазина для API Yookassa');
        }

        return $shopId;
    }

    private function getSecretKey(): string
    {
        $secretKey = config('yookassa.secretKey');

        if (strlen($secretKey) === 0) {
            throw new \LogicException('Не заполнен секретный ключ для API Yookassa');
        }

        return $secretKey;
    }

    public function isPaid(string $externalId): bool
    {
        $payment = $this->client->getPaymentInfo($externalId);

        return $payment->getPaid();
    }

    public function confirmPayment(string $externalId, Money $amount): void
    {
        if (!$this->isPaid($externalId)) {
            throw new \Exception("Попытка подтверждения неоплаченного заказа, ID: {$externalId}");
        }

        $data = [
            'amount' => [
                'value' => $amount->toString(),
                'currency' => self::DEFAULT_CURRENCY,
            ],
        ];

        $this->client->capturePayment($data, $externalId);
    }

    public function isCanceled(string $externalId): bool
    {
        $payment = $this->client->getPaymentInfo($externalId);

        return $payment->status === PaymentStatus::CANCELED;
    }
}
