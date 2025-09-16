<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="76x76" href="admin/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="admin/assets/img/logos/Logo MBS Corp.png">
    <title>Login - Office Management System</title>
    <style>
        /* CSS Reset dan Variabel */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #2980b9;
            --light-color: #ecf0f1;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --box-shadow: 0 8px 90px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #e2e2e2;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        /* Container Utama */
        .login-container {
            display: flex;
            width: 85%;
            max-width: 1000px;
            height: 550px;
            background: white;

            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }

        /* Bagian Kiri (Gambar) */
        .login-left {
            flex: 1;

            background: linear-gradient(#003d8de9, #5ce1e69f),
                        url('admin/assets/img/building.jpeg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .brand {
            position: absolute;
            top: 30px;
            left: 40px;
            font-size: 24px;
            font-weight: 700;
        }

        .welcome-text h1 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .welcome-text p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
        }

        /* Bagian Kanan (Form) */
        .login-right {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            color:
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #777;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--primary-color);
        }

        .form-group input {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 15px;
            transition: var(--transition);
        }

        .form-group input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 42px;
            cursor: pointer;
            color: #777;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .forgot-password:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .login-button:hover {
            background-color: var(--accent-color);
        }

        .error-message {
            color: var(--error-color);
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }

        /* Responsivitas */
        @media (max-width: 900px) {
            .login-container {
                flex-direction: column;
                height: auto;
                width: 90%;
                margin: 30px 0;
            }

            .login-left {
                padding: 30px;
                text-align: center;
            }

            .brand {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 20px;
            }

            .login-right {
                padding: 30px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                width: 95%;
            }

            .login-left, .login-right {
                padding: 20px;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
            }

            .forgot-password {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Bagian Kiri dengan Gambar dan Teks -->
        <div class="login-left">
            <div class="brand">SIM MBS</div>
            <div class="welcome-text">
                <h1>Selamat Datang</h1>
                <p>Sistem Manajemen Perkantoran yang membantu Anda mengelola operasional kantor dengan lebih efisien dan terintegrasi.</p>
            </div>
        </div>

        <!-- Bagian Kanan dengan Form Login -->
        <div class="login-right">
            <div class="login-header">
                <h2>Masuk ke Akun Anda</h2>
                <p>Silahkan masukkan Username & Password Anda untuk mengakses sistem</p>
            </div>

            <form role="form" id="loginForm" method="POST" action="">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="name" value="{{ old('name') }}" placeholder="Masukkan username Anda" required>
                    <div class="error-message" id="usernameError">Username harus diisi</div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>
                    <span class="password-toggle" id="passwordToggle">👁️</span>
                    <div class="error-message" id="passwordError">Password harus diisi</div>
                </div>

                <button type="submit" class="login-button">Masuk</button>
            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const passwordInput = document.getElementById('password');
                    const passwordToggle = document.getElementById('passwordToggle');

                    passwordToggle.addEventListener('click', function() {
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);
                        this.textContent = type === 'password' ? '👁️' : '🙈';
                    });
                });
            </script>
        </div>
    </div>

</body>
</html>
