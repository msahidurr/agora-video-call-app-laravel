<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="d-flex max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($users as $user)
                @php
                    $channelName = str_replace(' ', '-', auth()->user()->id);
                    $channelName .= "-channel-";
                    $channelName .= $user->id;
                @endphp

                <div class="card" style="width: 18rem; margin-left:10px;">
                    <div class="card-body">
                        <h5 class="card-title">Name: {{ $user->name }}</h5>
                        <a href="#" class="btn btn-primary" onclick="callJoin(`{{ $channelName }}`, `{{ $user->id }}`)">Call</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="d-flex justify-content-center p-2 d-none video-call" id="draggable" >
        <div class="p-2 rounded shadow-lg position-relative">
            <div id="local" class="d-flex p-1 justify-content-center rounded position-absolute local shadow-lg "></div>
            <div id="remote" class="d-flex p-1 justify-content-center rounded remote shaow-lg "></div>
            <div class="d-flex justify-content-center">
                <i class="fa fa-video-camera" id="btnCam" aria-hidden="true "></i>
                <i class="fa fa-microphone " id="btnMic" aria-hidden="true "></i><i class="fa fas fa-phone " id="btnPlug" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    
    @push('script')
        <script src="{{ asset('js/agora-video-call.js') }}?v=0.1"></script>
        <script>
            let options = {
                appId: `{{env('AGORA_APP_ID')}}`,
                channel: null,
                token: null,
            }

            async function callJoin(channelName, receiverUserId) {
                const tokenResponse = await fetch('/agora-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({
                        channelName: channelName,
                        receiverUserId: receiverUserId
                    })
                });

                const { status, message, data } = await tokenResponse.json();

                if(status == true) {
                    options.channel = channelName
                    options.token = data.token
                    
                    $("#btnPlug").addClass('fa-window-close');
                    $("#btnPlug").removeClass('fas fa-phone');
                    $("#btnPlug").css('color', 'red');
                    await startOneToOneVideoCall()
                    $(".video-call").removeClass('d-none');
                } else {
                    alert(message)
                }
            };
        </script>
    @endpush
</x-app-layout>