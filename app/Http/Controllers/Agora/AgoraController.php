<?php

namespace App\Http\Controllers\Agora;

use Illuminate\Http\Request;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Http\Controllers\Controller;
use Pusher\Pusher;

class AgoraController extends Controller
{
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
        $this->notifyClients('video-call-channel', 'client-video-call-started', [
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

    public function joinCall(Request $request)
    {
        $data = $request->all();
        return view('join-call', compact('data'));
    }

    public function getTokenSalon(Request $request, $salon)
    {
        // $salonInfo = Salon::where('name', $salon)->first();
        // $appId = "YOUR_AGORA_APP_ID";
        // $appCertificate = "YOUR_AGORA_APP_CERTIFICATE";
        // $channelName = $salon;
        // $uid = Auth::user()->id;
        // $expirationTimeInSeconds = 86400;
        // $currentTimeStamp = time();
        // $privilegeExpiredTs = $currentTimeStamp + $expirationTimeInSeconds;

        // $token = RtcTokenBuilder::buildTokenWithUid($appId, $appCertificate, $channelName, $uid, RtcTokenBuilder::RolePublisher, $privilegeExpiredTs);

        // // Notify clients using Pusher
        // $this->notifyClients('video-call-group-' . $salonInfo->name, 'client-group-video-call-started', [
        //     'callLink' => url("/room?channel={$salonInfo->id}&token=$token"),
        //     'actionValue' => $request->get('receiver_user_id')
        // ]);

        // return response()->json(['token' => $token, 'uid' => $uid]);
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

        $pusher->trigger('my-video-call-channel', 'my-call-event', $data);
    }
}
