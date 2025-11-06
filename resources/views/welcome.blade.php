<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bastion | Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        /* body {
            font-family: 'Poppins', sans-serif;
            background-color: #0b1d2a;
            color: white;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: radial-gradient(circle at top left, #0e2534, #07141d);
        } */

        body {
            font-family: 'Poppins', sans-serif;
            color: white;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;

            /* Background image */
            background: url("{{ asset('images/bg-img.jpg') }}") no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 80%;
            max-width: 1000px;
        }

        .left {
            width: 50%;
        }

        .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 280px;
            margin-right: 8px;
        }

        h2 {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        p {
            color: #a0a8b1;
            margin-bottom: 40px;
        }

        .buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            background-color: #00c3b3;
            color: white;
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn:hover {
            background-color: #00e6d3;
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid #00c3b3;
        }

        .btn-outline:hover {
            background-color: #00c3b3;
        }

        .right img {
            width: 380px;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                text-align: center;
            }
            .right img {
                width: 250px;
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <div class="logo">
                <img src="{{ asset('images/bastionlogo.png') }}" alt="Logo">
                {{-- <h1>BASTION</h1> --}}
            </div>
            <h2>Welcome to <b class="text-[#00c3b3]">Bastion</b></h2>
            <p><b>Where Security Meets Intelligence.</b><br>
                Protecting systems, empowering users.</p>
            <div class="buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline">Sign Up</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>

        {{-- <div class="right">
            <img src="https://via.placeholder.com/400x400.png?text=Flower+Art" alt="Flower Illustration">
        </div> --}}
    </div>
</body>
</html>
