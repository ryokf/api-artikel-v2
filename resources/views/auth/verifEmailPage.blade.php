<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eee;
            height: 100vh;
        }

        .container {
            margin: auto;
            padding-left: 20px;
            padding-right: 20px;
            background-color: #fff;
            max-width: 500px;
            box-sizing: border-box;
        }

        .btn-verif {
            padding: 10px;
            margin-top: 30px;
            margin-bottom: 30px;
            width: 100px;
            height: 45px;
            background: #333;
            border-radius: 10px;
            text-decoration: none;
        }

        .btn-verif span {
            color: white;
        }

        p {
            color: #aaa;
            display: flex;
            word-break: break-all;
        }

        .link {
            color: rgb(0, 137, 216);
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Hello, {{ $username }}</h1>
        <p>Please click the button below to verify your email address.</p>
        <a class="btn-verif" href="http://{{ request()->getHost() }}:8000/api/verify/{{ $hashedEmail }}">
            <span class="btn btn-primary">verify email address</span>
        </a>
        <p>If you did not create an account, no further action is required.</p>

        <p>regards,<br>Confess News</p>

        <hr>
        <p>
            If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your
            web browser:</p>
        <p class="link">http://{{ request()->getHost() }}:8000/api/verify/{{ $hashedEmail }}</p>
    </div>
</body>

</html>
