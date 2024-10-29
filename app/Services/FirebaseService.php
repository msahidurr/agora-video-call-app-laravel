<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\AndroidConfig;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($deviceToken, $token, $channelName)
    {
        $config = AndroidConfig::fromArray([
            'priority' => 'high',
        ]);

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withAndroidConfig($config)
            ->withData([
                'token' => $token,
                'channel' => $channelName,
            ]);

        return $this->messaging->send($message);
    }
}
