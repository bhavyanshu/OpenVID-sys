<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Verify Your Email Address</h2>

        <div>
            Welcome to BTD business portal. Your account is ready. You need to verify your email address in order to unlock all services.<br>
            Please follow the link below to verify your email address
            <a href="{{ URL::to('user/verify/' . $confirmcode) }}">{{ URL::to('user/verify/' . $confirmcode) }}</a>.<br/>

        </div>

    </body>
</html>
