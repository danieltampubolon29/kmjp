<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <title>KMJP |</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>

<body>
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="sign-in-form active" id="login-form">
                    @csrf
                    <h2 class="title">Login</h2>
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required />
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn solid">Login</button>
                </form>

                <!-- Register Form -->
                <form action="{{ route('register') }}" method="POST" class="sign-up-form" id="register-form">
                    @csrf
                    <h2 class="title">Register</h2>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" placeholder="Username" value="{{ old('name') }}" required />
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required />
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" required />
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                    </div>
                    <button type="submit" class="btn">Register</button>
                </form>
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Belum Punya Akun?</h3>
                    <p>Daftar sekarang dan nikmati layanan terbaik kami.</p>
                    <button class="btn transparent" id="sign-up-btn">Register</button>
                </div>
                <img src="{{ asset('img/log.svg') }}" class="image" alt="" />
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>Sudah Punya Akun?</h3>
                    <p>Login untuk melanjutkan ke dashboard Anda.</p>
                    <button class="btn transparent" id="sign-in-btn">Login</button>
                </div>
                <img src="{{ asset('img/register.svg') }}" class="image" alt="" />
            </div>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
   
</body>

</html>