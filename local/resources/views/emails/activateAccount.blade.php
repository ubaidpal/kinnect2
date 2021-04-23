<p>Dear {{$name}}</p>
<p>Thank you for registeration with Kinnect2. The email used during registration is below.</p>
<div>Email: {{@$email}}</div>
<div>{{ Lang::get('auth.clickHereActivate') }}</div>
<a href="{{ url('activate/'.$code) }}" >
{{ url('activate/') }}
</a>