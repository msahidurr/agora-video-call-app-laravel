<?php

namespace App\Http\Controllers\Agora;

use Kreait\Firebase\Exception\Messaging\NotFound;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use App\Models\User;
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

        // Notify clients using firebase
        $user = User::find($request->receiverUserId);
        $deviceToken = $user->device_token;

        if (empty($deviceToken)) {
            return response()->json([
                'status' => false,
                'message' => 'Patient is unavailable for online consultation at the moment.',
                'data' => [],
            ]);
        }

        try {
            $this->firebaseService->sendNotification($deviceToken, $token, $channelName);
        } catch (NotFound $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Success',
            'data' => [
                'token' => $token
            ],
        ]);
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
