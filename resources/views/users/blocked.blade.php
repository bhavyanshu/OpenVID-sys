<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Account is Blocked</title>
	{!! Html::style('css/app.css') !!}
</head>
<body>
<div class="container spark-screen">
    <div class="row" style="padding:10px;">
			@if(Session::has('message'))
			<br>
			<div class="alert alert-info errors">{{ Session::get('message') }}</div>
			@endif
			<h1>Account is blocked</h1>
			<p>It seems your account is blocked. There are number of reasons why your account might be blocked.</p>
			<ul>
				<li><strong>Email not verified</strong> : You have still not verified your email address. Kindly check your mailbox and click on the link provided in the mail.</li>
				<li><strong>Service Abuse</strong> : Administrators have the authority to block any accounts if they notice any abuse of service.</li>
			</ul>
			<p>For any further details, please contact administrator.</p>
			<p>You may {{ Html::link('user/logout', 'logout') }} for now.</p>
		</div>
	</div>
</body>
</html>
