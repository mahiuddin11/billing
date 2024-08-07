<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Thank You</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body>
        <div class="vh-100 d-flex justify-content-center align-items-center">
            <div>
                <div class="mb-4 text-center">
                    @if (session()->has('success'))
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="75" height="75"
                    fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </svg>
                    @elseif (session()->has('failed'))
                    <svg fill="#ff0000" width="75" height="75" viewBox="0 -8 528 528" xmlns="http://www.w3.org/2000/svg" stroke="#ff0000">

                        <g id="SVGRepo_bgCarrier" stroke-width="0"/>

                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>

                        <g id="SVGRepo_iconCarrier">

                        <title>fail</title>

                        <path d="M264 456Q210 456 164 429 118 402 91 356 64 310 64 256 64 202 91 156 118 110 164 83 210 56 264 56 318 56 364 83 410 110 437 156 464 202 464 256 464 310 437 356 410 402 364 429 318 456 264 456ZM264 288L328 352 360 320 296 256 360 192 328 160 264 224 200 160 168 192 232 256 168 320 200 352 264 288Z"/>
                        </g>
                    </svg>
                    @endif


                </div>
                <div class="text-center">
                    @if (session()->has('success'))
                        <h1>{{Session::get('success')}}!</h1>
                        <p>Happy Internet Use,</p>
                    @elseif (session()->has('failed'))
                        <h1>{{Session::get('failed')}}</h1>
                        <p>Please Try Again,</p>
                    @endif
                </div>
            </div>
    </body>

</html>

