<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    public function sendToAdmin(string $message): void
    {
        if (! config('services.whatsapp.enabled')) {
            return;
        }

        $adminNumber = config('services.whatsapp.admin_number');
        $url = config('services.whatsapp.gateway_url');
        $token = config('services.whatsapp.token');

        if (! $adminNumber || ! $url || ! $token) {
            Log::warning('WhatsApp notification config belum lengkap.');
            return;
        }

        try {
            Http::withHeaders([
                'Authorization' => $token,
            ])->post($url, [
                'target' => $adminNumber,
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal kirim WhatsApp notification: ' . $e->getMessage());
        }
    }
}