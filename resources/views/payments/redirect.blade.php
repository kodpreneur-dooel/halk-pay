<!doctype html>
<html lang="mk">
<head>
    <meta charset="utf-8">
    <title>Redirecting to bank…</title>
    <meta http-equiv="refresh" content="10">
</head>
<body onload="document.getElementById('halkpay-form').submit()">
<p>Redirecting you to the bank for secure payment…</p>
<form id="halkpay-form" method="POST" action="{{ $url }}">
    <input type="hidden" name="encoding" value="UTF-8">
    @foreach($params as $k => $v)
        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
    @endforeach
    <noscript>
        <button type="submit">Continue to payment</button>
    </noscript>
</form>
</body>
</html>
