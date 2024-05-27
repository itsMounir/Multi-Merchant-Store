<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class MobileNotificationServices
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(config('services.firebase.credentials.file'));

        $this->messaging = $firebase->createMessaging();
    }

    public function sendNotification($deviceToken, $title, $body)
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);

        try {
            $this->messaging->send($message);
            return true;
        } catch (\Exception $e) {
            Log::error('Firebase Notification Error: ' . $e->getMessage());
            return false;
        }
    }


    public function subscribeToTopic(Request $request)
    {
        $deviceToken = $request->input('device_token');
        $topic = $request->input('topic');

        try {
            $this->messaging->subscribeToTopic($topic, $deviceToken);
            return response()->json(['success' => true, 'message' => 'Subscribed to topic successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('Firebase Topic Subscription Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to subscribe to topic'], 400);
        }
    }

    public function sendNotificationToTopic($topic, $title, $body)
    {
        try {
            $notification = Notification::create($title, $body);
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification);

            $this->messaging->send($message);
            return response()->json(['success' => true, 'message' => 'Notification sent successfully!'], 200);
        } catch (\Exception $e) {
            Log::error('Firebase Topic Notification Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send notification'], 400);
        }
    }
}
