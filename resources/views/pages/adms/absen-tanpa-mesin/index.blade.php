<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action="{{ url('/love-you-with-all-my-heart/simpan') }}" method="POST">
    @csrf
    Nama
    <select name="employee_id" id="employee_id">
        @foreach($users as $user)
            <option value="{{ $user->absent_id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    Checkin
    <input type="time" class="form-control form-control-solid" name="clock_in">
    Checkout
    <input type="time" class="form-control form-control-solid" name="clock_out">
    <button type="submit">Simpan</button>
</form>


</body>
</html>