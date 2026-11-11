<?php

namespace App\FirebaseMessenger;

use Google\Client;
use Illuminate\Support\Facades\Http;

class FirebaseMessenger
{
    protected string $accessToken;

    public function __construct()
    {
        // Load Firebase Service Account
        $client = new Client();
        $client->setAuthConfig('public/assets/firebase/firebase_credentials.json');
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $this->accessToken = $client
            ->fetchAccessTokenWithAssertion()['access_token'];
    }

    public function send(
        string $fcmToken,
        string $subject,
        string $body,
        ?string $profileImage = null,
        ?string $url = null,
        ?string $taskName = null
    ): bool {
        if (!$fcmToken) {
            return false;
        }

        $message = [
            "message" => [
                "token" => $fcmToken,
                "notification" => [
                    "title" => "Message from {$subject}",
                    "body"  => $body,
                    "image" => $profileImage,
                ],
                "data" => array_filter([
                    "taskName" => $taskName,
                    "taskUrl"  => $url,
                    "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                ]),
            ],
        ];

        $response = Http::withToken($this->accessToken)
            ->post(
                "https://fcm.googleapis.com/v1/projects/laramarkpush/messages:send",
                $message
            );

        return $response->successful();
    }
}
