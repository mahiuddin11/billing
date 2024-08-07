<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP Billing Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Muli:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style-with-prefix.css') }}">
    <style>
        .srouce {
            text-align: center;
            color: #ffffff;
            padding: 10px;
        }
    </style>
</head>

<body>

    <div class="main-container">
        <div class="form-container">
            <div class="form-body">
                @if (session()->has('failed'))
                <p style="text-align: center;color:red">{{session()->get('failed')}}</p>
                @endif
                <h3 style="text-align:center">Welcome</h3>
                <form method="POST" action="{{ route('customer.login') }}" class="the-form">
                    @csrf

                    <label for="email">User name</label>
                    <input type="text" name="username" id="user" placeholder="Enter your UserName">
                    {!! $errors->first('username', '<p class="help-block">:message</p>') !!}
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password">
                    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}

                    <input type="submit" value="Log In">

                </form>

            </div><!-- FORM BODY-->


        </div><!-- FORM CONTAINER -->
    </div>

</body>

</html>
