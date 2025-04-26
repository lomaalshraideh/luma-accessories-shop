<!DOCTYPE html>
<html lang="en">
<head>
    <title>Luma - Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ asset('style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Marcellus&display=swap" rel="stylesheet">
    <style>
      body {
        background-color: #f8f8f8;
        font-family: 'Jost', sans-serif;
      }

      h2, h4.login-title {
        font-family: 'Marcellus', serif;
      }

      .login-container {
        max-width: 600px;
        margin: 100px auto;
        background: white;
        padding: 60px 50px;
        border-radius: 16px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
      }

      .form-control {
        border: 2px solid #a67856;
        border-radius: 12px;
        height: 60px;
        font-size: 18px;
        padding: 0.75rem 1.25rem;
        margin-bottom: 20px;
      }

      .btn-custom {
        background-color: #3c3c3c;
        color: white;
        font-weight: bold;
        border-radius: 12px;
        height: 62px;
        font-size: 18px;
        width: 100%;
        transition: all 0.3s ease;
      }

      .btn-custom:hover {
        background-color: #222222;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        cursor: pointer;
        color: #f8f8f8;
      }

      .btn-custom:active {
        transform: translateY(1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      }

      .privacy-link {
        color: #a67856;
        font-family: 'Jost', sans-serif;
        font-size: 1rem;
        display: block;
        text-align: left;
        margin-top: 30px;
      }

      .login-title {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 15px;
      }

      .login-subtext {
        font-size: 18px;
        color: #555;
        margin-bottom: 35px;
      }

      h2 {
        font-size: 32px;
      }
    </style>
</head>
<body>
    <div class="login-container text-center">
        <h2 class="mb-4">Luma</h2>
        <h4 class="mb-1 login-title">Log in</h4>
        <p class="login-subtext mb-4">Enter your email and password</p>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3 text-start">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3 text-start">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-custom">Continue</button>
            </div>
            <div class="mt-3 text-center">
                <p class="">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register</a></p>
            </div>
        </form>
        <a href="#" class="privacy-link">Privacy</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</body>
</html>
