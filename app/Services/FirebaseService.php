<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        // Cari kredensial Firebase di root folder atau dari .env
        $credentialsPath = base_path(env('FIREBASE_CREDENTIALS', 'firebase-adminsdk.json'));
        
        $factory = (new Factory);
        
        // Hanya inisiasi jika file kredensial ada untuk menghindari fatal error
        if (file_exists($credentialsPath)) {
            $factory = $factory->withServiceAccount($credentialsPath);
            $this->messaging = $factory->createMessaging();
        } else {
            \Log::warning("Firebase credentials file not found at: {$credentialsPath}. FCM will be disabled.");
        }
    }

    /**
     * Send notification to a specific device capability
     */
    public function sendToToken($fcmToken, $title, $body, $data = [])
    {
        if (!$this->messaging || !$fcmToken) {
            return false;
        }

        try {
            $messageConfig = [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ];

            if (!empty($data)) {
                $messageConfig['data'] = $data;
            }

            $message = CloudMessage::fromArray($messageConfig);

            $this->messaging->send($message);
            return true;
        } catch (\Throwable $e) {
            \Log::error('Firebase Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send multicast notification to multiple tokens
     * 
     * @param array $tokens Array of valid FCM tokens
     * @return \Kreait\Firebase\Messaging\MulticastSendReport|null
     */
    public function sendToTokens(array $tokens, $title, $body, $data = [])
    {
        if (!$this->messaging || empty($tokens)) {
            return null;
        }

        try {
            // Maximum tokens per multicast limit by Firebase is 500
            // The Firebase PHP SDK handles chunking automatically in sendMulticast()
            $messageConfig = [
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ];

            if (!empty($data)) {
                $messageConfig['data'] = $data;
            }

            $message = CloudMessage::fromArray($messageConfig);
            $report = $this->messaging->sendMulticast($message, $tokens);

            return $report;
        } catch (\Throwable $e) {
            \Log::error('Firebase Multicast Error: ' . $e->getMessage());
            return null;
        }
    }
}
