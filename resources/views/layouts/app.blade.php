<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer"
    />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            #draggable {
                position: absolute;
                cursor: move;
                z-index: 9999;
            }

            .local {
                width: 80px;
                height: 80px;
                background-color: #223344;
                top: 15px;
                right: 15px;
                z-index: 1;
            }

            .remote {
                width: 500px;
                height: 500px;
                background-color: #384552;
            }

            .fa,
            .fas {
                font-size: 32px;
                padding: 10px 25px;
            }

            .full {
                font-size: 24px;
                background-color: white;
                padding: 5px 10px;
                border-radius: 5px;
            }

            .roomMessage {
                display: flex;
                align-items: center;
                width: 100%;
                height: 100%;
                background: burlywood;
                justify-content: center;
            }
        </style> 
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Modal -->
            <div class="modal fade" id="myCallModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <a href="#" id="receive-call" class="btn btn-primary">Receive call</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.17.0.js"></script>
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;
        
            var pusher = new Pusher('92ee20fcd9089de139e0', {
              cluster: 'ap2'
            });
        
            var channel = pusher.subscribe('my-video-call-channel');
            channel.bind('my-call-event', function(data) {
                const userId = `{{ auth()->user()->id }}`;
                if(userId == data.receiverUserId) {     
                    sessionStorage.setItem('token', data.token);               
                    $("#receive-call").attr('href', data.callLink)
                    $('#myCallModal').modal('show');
                }
            });
          </script>
        @stack('script')
    </body>
</html>
