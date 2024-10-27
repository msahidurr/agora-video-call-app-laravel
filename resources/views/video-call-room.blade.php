<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="d-flex justify-content-center p-2 d-none video-call" id="draggable">
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
        <script>
            const client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });

            const TOKEN = sessionStorage.getItem('token');

            let options = {
                appId: 'ae90d8af316447888cdbfa82be5934ee',
                channel: `{{ request()->channel }}`,
                token: TOKEN,
            }
            $(".video-call").removeClass('d-none');
        </script>
        <script src="{{ asset('js/agora-video-call.js') }}?v=0.1"></script>
    @endpush
</x-app-layout>