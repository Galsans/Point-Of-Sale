<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
        }

        .card-login {
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #764ba2;
        }

        .btn-primary {
            background: #764ba2;
            border: none;
        }

        .btn-primary:hover {
            background: #667eea;
        }

        .login-footer a {
            color: #764ba2;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card card-login p-4 col-md-4 col-sm-8">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-primary">Welcome Back</h3>
                <p class="text-muted">Silahkan login untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    {{-- <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email"
                        value="{{ old('email') }}" required> --}}
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    {{-- <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password"
                        required> --}}
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                </div>

                <div class="text-center login-footer">
                    {{-- <small>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></small> --}}
                    {{-- <small>Belum punya akun? <a href="#">Daftar</a></small> --}}

                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
