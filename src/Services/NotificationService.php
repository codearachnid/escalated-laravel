<?php

namespace Escalated\Laravel\Services;

use Illuminate\Support\Facades\Http;

class NotificationService
{
    public function sendWebhook(string $event, array $payload): void
    {
        $url = config('escalated.notifications.webhook_url');

        if (! $url) {
            return;
        }

        $body = [
            'event' => $event,
            'payload' => $payload,
            'timestamp' => now()->toISOString(),
        ];

        $request = Http::timeout(10);

        // Sign the webhook payload if a secret is configured
        $secret = config('escalated.notifications.webhook_secret');
        if (! empty($secret)) {
            $signature = hash_hmac('sha256', json_encode($body), $secret);
            $request = $request->withHeaders([
                'X-Escalated-Signature' => $signature,
            ]);
        }

        $request->post($url, $body);
    }

    public function getConfiguredChannels(): array
    {
        return config('escalated.notifications.channels', ['mail', 'database']);
    }
}
