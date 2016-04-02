<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>New comment on {{$vul_unique_id}}</h2>
        <div>
            Please follow the link below to view the comment
            <a href="{{ URL::to('vulnerability/'.$vulnid.'#comment-'.$comment_id) }}">{{ URL::to('vulnerability/'.$vulnid.'#comment-'.$comment_id) }}</a>.<br/>
        </div>
    </body>
</html>
