<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Luma - Register Account</title>
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
            font-family: 'Jost', sans-serif; /* Changed from Arial to Jost to match main layout */
          }

          h2, h4.login-title {
            font-family: 'Marcellus', serif; /* Using Marcellus for headings like in main layout */
          }

          /* Rest of your existing styles remain unchanged */
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
            color: #f8f8f8; /* This was missing in register */
          }
          .btn-custom:active {
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        </style>
    </head>
    <body>
        <div class="login-container text-center">
            <h2 class="mb-4">Luma</h2>
            <h4 class="mb-1 login-title">Register</h4>
            <p class="login-subtext mb-4">Create your account to start shopping</p>

            <form method="POST" action="{{ route('register') }}" class="text-start">
                @csrf

                <div class="mb-3 text-start">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Full Name">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <input id="phone" type="number" class="form-control @error('phone_number') is-invalid @enderror"
                           name="phone_number" value="{{ old('phone_number') }}" required autocomplete="phone" placeholder="Phone Number">
                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" required autocomplete="new-password" placeholder="Password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <input id="password-confirm" type="password" class="form-control"
                           name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                </div>

                <div class="d-grid mb-4">
                    <button type="submit" class="btn btn-custom">Register</button>
                </div>

                <div class="text-center">
                    <p>Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login</a></p>
                </div>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
        </script>
    </body>
</html>
