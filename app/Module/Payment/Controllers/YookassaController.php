<?php

namespace App\Module\Payment\Controllers;

use App\Http\Controllers\Controller;
use App\Module\Payment\Entities\YookassaWebhook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class YookassaController extends Controller
{
    public function notify(Request $request)
    {
        try {
            $webhook = YookassaWebhook::create($request->getContent(), $request->headers->all());
        } catch (\Throwable $throwable) {
            return $this->response($throwable->getMessage(), 500);
        }

        try {
            $message = $webhook->getReplyMessage($request->getContent(), $request->ip());
        } catch (\Throwable $throwable) {
            $webhook->markAsFailed((string) $throwable);

            return $this->response($throwable->getMessage(), 500);
        }

        $webhook->markAsProcessed();

        return $this->response($message);
    }

    private function response(string $message, int $status = 200): Response
    {
        return response($message, $status)
            ->header('Content-Type', 'text/plain');
    }
}
