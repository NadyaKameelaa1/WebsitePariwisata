<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Login</title>
</head>

<body>
    <section class="hero">


        <div class="login-container">
            <form method="POST" action="proses_register.php">
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="username" placeholder="Nama pengguna" required />
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email_user" placeholder="Email pengguna" required />
                </div>

                <div class="input-group password-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" id="password" placeholder="Kata sandi" required />
                    <span class="eye-icon" onclick="togglePassword()">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </span>
                </div>

                <p class="register-link">Sudah punya akun? <b><a href="login.php">Login disini!</a></b></p>

                <button type="submit">Daftar</button>
            </form>
        </div>

    </section>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.className = 'fa-solid fa-eye-slash';
            } else {
                password.type = 'password';
                eyeIcon.className = 'fa-solid fa-eye';
            }
        }
    </script>
</body>

</html>