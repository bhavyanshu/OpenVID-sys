<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>{{$vul_unique_id}} Status has been updated</h2>
        <div>
            Please follow the link below to view the detailed report
            <a href="{{ URL::to('vulnerability/' . $vulnid) }}">{{ URL::to('vulnerability/' . $vulnid) }}</a>.<br/>
        </div>
    </body>
</html>
