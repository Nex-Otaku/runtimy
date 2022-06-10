<?php

namespace App\Module\Payment;

use App\Module\Common\Money;
use App\Module\Payment\Contracts\PaymentOrder;
use App\Module\Payment\Entities\FakeOrder;
use App\Module\Payment\Entities\Payment;
use YooKassa\Client;

class YookassaApi
{
    public function createPayment(PaymentOrder $paymentOrder, Money $amount): Payment
    {
        $payment = Payment::create($paymentOrder);

        $client = new Client();
        $client->setAuth($this->getShopId(), $this->getSecretKey());

        // https://yookassa.ru/developers/payment-acceptance/integration-scenarios/manual-integration/other/sbp#create-payment-qr
        // 1. Создаём платёж
        // 2. Обновляем статус платежа по уведомлению
        // 3. При успешном платеже завершаем оплату
        $paymentResponse = $client->createPayment(
            [
                'amount' => [
                    'value' => $amount->toString(),
                    'currency' => 'RUB',
                ],
                'payment_method_data' => [
                    'type' => 'sbp',
                ],
                'confirmation' => [
                    'type' => 'qr',
                ],
                'description' => 'Оплата доставки по заказу №' . $paymentOrder->getModelId(),
            ],
            uniqid('', true)
        );

        var_dump($paymentResponse);

//        $payment = $client->createPayment(
//            array(
//                'amount' => array(
//                    'value' => 100.0,
//                    'currency' => 'RUB',
//                ),
//                'confirmation' => array(
//                    'type' => 'redirect',
//                    'return_url' => 'https://www.merchant-website.com/return_url',
//                ),
//                'capture' => true,
//                'description' => 'Заказ №1',
//            ),
//            uniqid('', true)
//        );

        // TODO
        return new Payment();
    }

    public function createTestPayment(): void
    {
        $this->createPayment(new FakeOrder(), Money::createFromString('50000'));
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
}