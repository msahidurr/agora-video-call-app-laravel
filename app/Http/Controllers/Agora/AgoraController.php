<?php

namespace App\Http\Controllers\Agora;

use Illuminate\Http\Request;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Http\Controllers\Controller;

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

        return response()->json(['token' => $token]);
    }
}
