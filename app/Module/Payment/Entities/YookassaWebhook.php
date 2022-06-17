<?php

namespace App\Module\Payment\Entities;

use App\Module\Payment\Models\YookassaWebhook as YookassaWebhookModel;
use App\Module\Payment\YookassaApi;
use Illuminate\Support\Str;
use YooKassa\Model\Notification\NotificationFactory;
use YooKassa\Model\NotificationEventType;
use YooKassa\Model\PaymentInterface;
use YooKassa\Model\RefundInterface;

class YookassaWebhook
{
    private const STATUS_ACCEPTED = 'accepted';
    private const STATUS_PROCESSED = 'processed';
    private const STATUS_FAILED = 'failed';

    private YookassaWebhookModel $yookassaWebhook;

    private function __construct(
        YookassaWebhookModel $yookassaWebhook
    ) {
        $this->yookassaWebhook = $yookassaWebhook;
    }

    public static function create(string $content, array $headers): self
    {
        $yookassaWebhook = new YookassaWebhookModel(
            [
                'request_content' => Str::limit($content, 65535),
                'request_headers' => Str::limit(json_encode($headers), 65535),
                'status' => self::STATUS_ACCEPTED,
                'error' => '',
            ]
        );

        $yookassaWebhook->saveOrFail();

        return new self($yookassaWebhook);
    }

    public function markAsProcessed(): void
    {
        $this->yookassaWebhook->status = self::STATUS_PROCESSED;
        $this->yookassaWebhook->saveOrFail();
    }

    public function markAsFailed(string $error): void
    {
        $this->yookassaWebhook->status = self::STATUS_FAILED;
        $this->yookassaWebhook->error = Str::limit($error, 65535);
        $this->yookassaWebhook->saveOrFail();
    }

    public function getReplyMessage(string $content, string $ip): string
    {
        $client = new \YooKassa\Client();

        if (!$client->isNotificationIPTrusted($ip)) {
            throw new \Exception('Некорректный IP');
        }

        $data = json_decode($content, true);

        $factory = new NotificationFactory();
        $notificationObject = $factory->factory($data);
        $responseObject = $notificationObject->getObject();

        if ($responseObject === null) {
            throw new \Exception('Некорректный запрос');
        }

        if ($notificationObject->getEvent() === NotificationEventType::PAYMENT_SUCCEEDED) {
            /** @var PaymentInterface $responseObject */
            $this->successPayment($responseObject);
        } elseif ($notificationObject->getEvent() === NotificationEventType::PAYMENT_WAITING_FOR_CAPTURE) {
            /** @var PaymentInterface $responseObject */
            $this->confirmPayment($responseObject);
        } elseif ($notificationObject->getEvent() === NotificationEventType::PAYMENT_CANCELED) {
            /** @var PaymentInterface $responseObject */
            $this->cancelPayment($responseObject);
        } elseif ($notificationObject->getEvent() === NotificationEventType::REFUND_SUCCEEDED) {
            /** @var RefundInterface $responseObject */
            $this->doRefund($responseObject);
        } else {
            throw new \Exception('Неподдерживаемое событие');
        }

        return 'OK';
    }

    private function successPayment(PaymentInterface $responseObject): void
    {
        $yookassaPayment = YookassaPayment::getById((int) $responseObject->id);

        if (!$yookassaPayment->canSucceed()) {
            throw new \Exception("Заказ уже был подтверждён или отклонён. YP ID: {$yookassaPayment->getModelId()}");
        }

        if (!YookassaApi::instance()->isPaid($responseObject->id)) {
            throw new \Exception("Заказ не оплачен в Yookassa. YK ID: {$responseObject->id}");
        }

        $yookassaPayment->complete();

        $mainPayment = $yookassaPayment->getMainPayment();
        $mainPayment->complete();
    }

    private function confirmPayment(PaymentInterface $responseObject): void
    {
        $yookassaPayment = YookassaPayment::getById((int) $responseObject->id);

        if (!$yookassaPayment->canConfirm()) {
            throw new \Exception("Заказ уже был подтверждён или отклонён. YP ID: {$yookassaPayment->getModelId()}");
        }

        if (!YookassaApi::instance()->isPaid($responseObject->id)) {
            throw new \Exception("Заказ не оплачен в Yookassa. YK ID: {$responseObject->id}");
        }

        YookassaApi::instance()->confirmPayment($responseObject->id, $yookassaPayment->getAmount());

        $yookassaPayment->complete();

        $mainPayment = $yookassaPayment->getMainPayment();
        $mainPayment->complete();
    }

    private function cancelPayment(PaymentInterface $responseObject): void
    {
        $yookassaPayment = YookassaPayment::getById((int) $responseObject->id);

        if (!$yookassaPayment->canCancel()) {
            throw new \Exception("Заказ уже был подтверждён или отклонён. YP ID: {$yookassaPayment->getModelId()}");
        }

        if (!YookassaApi::instance()->isCanceled($responseObject->id)) {
            throw new \Exception("Заказ не отменён в Yookassa. YK ID: {$responseObject->id}");
        }

        $yookassaPayment->cancel($responseObject);

        $mainPayment = $yookassaPayment->getMainPayment();
        $mainPayment->cancel();
    }

    private function doRefund(RefundInterface $responseObject): void
    {
        $yookassaPayment = YookassaPayment::getById((int) $responseObject->id);

        if (!$yookassaPayment->canRefund()) {
            throw new \Exception("Средства не могут быть возвращены. YP ID: {$yookassaPayment->getModelId()}");
        }

        if (!YookassaApi::instance()->isCanceled($responseObject->id)) {
            throw new \Exception("Заказ не отменён в Yookassa. YK ID: {$responseObject->id}");
        }

        $yookassaPayment->refund($responseObject);

        $mainPayment = $yookassaPayment->getMainPayment();
        $mainPayment->refund();
    }
}
