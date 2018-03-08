<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<form method="POST">
    @csrf
    <input type="text" name="username" placeholder="{{ __('messages.username') }}" autocomplete="off">
    <input type="password" name="password" placeholder="{{  __('messages.password') }}" autocomplete="off"><br />
    <input type="submit" name="submit" value="{{  __('messages.submit') }}">
    <span>{{ $loginMsg }}</span>
</form>

</body>
</html>