<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dian Laundry | Login</title>
        <link rel="stylesheet" href="<?=url('_assets/css/login.css')?>?v={{ filemtime(public_path('_assets/css/login.css')) }}">
        <link rel="shortcut icon" href="<?= url('_assets/img/logo/logo2.png') ?>" type="image/x-icon">
    </head>
    <body>
        
    @if ($errors->any())
        <div class="overlay">
            <div class="boxSalah">
                <a href="{{ route('login') }}" class="close">&times;</a>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

        <div class="login-page">
            <div class="login-panel">
                <div class="login-avatar">
                    <img src="{{ asset('_assets/img/logo3.png') }}" alt="Dian Laundry">
                </div>
                <h1>Login</h1>
                

                <form action="{{ route('login') }}" method="post" class="login-form">
                    @csrf
                    <label class="login-field">
                        <span class="login-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4"></circle>
                                <path d="M4 20c0-4 4-6 8-6s8 2 8 6"></path>
                            </svg>
                        </span>
                        <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                    </label>

                    <label class="login-field">
                        <span class="login-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                                <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                            </svg>
                        </span>
                        <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
                    </label>

                    <button type="submit" class="login-btn">Login</button>
                </form>
            </div>
        </div>
    </body>
</html>
