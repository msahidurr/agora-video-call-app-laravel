<?php

namespace App\Http\Controllers\Agora;

use Illuminate\Http\Request;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Pusher\Pusher;

class AgoraController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function generateToken(Request $request)
    {
        $appID = env('AGORA_APP_ID');
        $appCertificate = env('AGORA_APP_CERTIFICATE');
        $channelName = $request->channelName;
        $uid = $request->uid ?? 0; // 0 for web users
        $role = RtcTokenBuilder::RolePublisher;
        $expireTimeInSeconds = 3600;
        $currentTimestamp = now()->timestamp;
        $privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

        $token = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);

        // Notify clients using Pusher
        $this->notifyClients('my-video-call-channel', 'my-call-event', [
            'callLink' => url("/video-call-room?channel=$channelName"),
            'channel' => $channelName,
            'token' => $token,
            'receiverUserId' => $request->get('receiverUserId')
        ]);

        return response()->json(['token' => $token, 'uid' => $uid]);
    }

    public function videoCallRoom(Request $request)
    {
        $data = $request->all();
        return view('video-call-room', compact('data'));
    }

    private function notifyClients($channel, $event, $data)
    {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => false,
            ]
        );

        $pusher->trigger($channel, $event, $data);
    }
}
