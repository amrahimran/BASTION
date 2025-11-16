<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url={{ route('dashboard') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #fefefe;
            font-family: sans-serif;
        }
        .message-box {
            background: #ffe5e5;
            padding: 2rem;
            border: 1px solid #ff4d4d;
            border-radius: 12px;
            text-align: center;
            color: #ff1a1a;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>{{ $message }}</h1>
        <p>Redirecting to dashboard in 3 seconds...</p>
    </div>
</body>
</html>
