<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Reset your password</h2>

        <div>
            Please click on the link below to reset your password.<br>
            {{ URL::to('user/password/reset/'.$token) }}.<br/>

        </div>

    </body>
</html>
