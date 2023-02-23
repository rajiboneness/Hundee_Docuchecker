<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Borrower E-signing</title>
</head>
<body>
    <p>Hello, {{$name}}</p>

    <p>This is an auto generated email from Hundee</p>

    {{-- <p>To continue your E-signing process, <a href="{{ route('zoop.borrower', $request_id) }}">Click here</a></p> --}}
    <p>To continue your E-signing process, <a href="{{ $route }}">Click here</a></p>
</body>
</html>