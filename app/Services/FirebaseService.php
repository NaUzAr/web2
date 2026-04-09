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
            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            \Log::error('Firebase Notification Error: ' . $e->getMessage());
            return false;
        }
    }
}
