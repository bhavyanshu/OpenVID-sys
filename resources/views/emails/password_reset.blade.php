<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Reset your BTD password</h2>

        <div>
            Please follow the link below to reset your password.<br>
            <a href="{{ URL::to('user/password/reset/'.$token) }}">{{ URL::to('user/password/reset/'.$token) }}</a>.<br/>
        </div>
    </body>
</html>
