<?php
session_start();
require_once 'koneksi.php'; // Jika pakai database, kalau tidak ada tetap aman

// Jika sudah login, redirect
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // === LOGIN DEMO (TANPA DATABASE) ===
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_id'] = 1;
        $_SESSION['username'] = 'admin';
        $_SESSION['nama_lengkap'] = 'Administrator Sistem';
        $_SESSION['level'] = 'superadmin';

        // Update last login jika tabel admin tersedia
        if (isset($conn)) {
            mysqli_query($conn, "UPDATE admin SET terakhir_login = NOW() WHERE username = 'admin'");
        }

        header('Location: dashboard.php');
        exit();
    }

    // === LOGIN MENGGUNAKAN DATABASE (Jika ADA) ===
    if (isset($conn)) {
        $sql = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $admin = mysqli_fetch_assoc($result);

            // Password belum di-hash (sesuai file Anda)
            if ($password === $admin['password']) {
                $_SESSION['admin_id'] = $admin['id_admin'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['nama_lengkap'] = $admin['nama_lengkap'];
                $_SESSION['level'] = $admin['level'];

                mysqli_query($conn, "UPDATE admin SET terakhir_login = NOW() WHERE id_admin = {$admin['id_admin']}");

                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Password salah!';
            }
        } else {
            $error = 'Username tidak ditemukan!';
        }
    } else {
        $error = 'Koneksi database bermasalah!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SMA TUNAS HARAPAN</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            padding: 20px;
        }
        .login-container { width: 100%; max-width: 450px; animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from{opacity:0; transform:translateY(30px);} to{opacity:1; transform:translateY(0);} }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .login-header { text-align: center; margin-bottom: 30px; }
        .logo {
            width: 100px; height: 100px;
            background: linear-gradient(135deg,#1a2980,#26d0ce);
            border-radius: 50%; display:flex; align-items:center; justify-content:center;
            margin:0 auto 20px; box-shadow:0 10px 20px rgba(26,41,128,0.3);
        }
        .logo i { font-size:48px; color:white; }
        .school-name { color:#1a2980; font-weight:700; font-size:28px; margin-bottom:5px; }
        .school-motto { color:#666; font-size:16px; font-style:italic; }

        .input-group { border-radius: 10px; overflow: hidden; border: 2px solid #e0e0e0; transition: all 0.3s; }
        .input-group:focus-within { border-color:#1a2980; box-shadow:0 0 0 0.25rem rgba(26,41,128,0.25); }
        .input-group-text { background:#f8f9fa; border:none; color:#666; padding:12px 15px; }
        .form-control { border:none; padding:12px 15px; font-size:16px; }
        .password-toggle { cursor:pointer; background:#f8f9fa; border:none; padding:12px 15px; }
        .btn-login {
            background: linear-gradient(135deg,#1a2980,#26d0ce);
            color:white; border:none; padding:14px; border-radius:10px;
            font-weight:600; font-size:16px; width:100%; transition:0.3s;
        }
        .btn-login:hover { transform:translateY(-3px); box-shadow:0 8px 20px rgba(26,41,128,0.4); }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo"><i class="fas fa-graduation-cap"></i></div>
                <h1 class="school-name">SMA TUNAS HARAPAN</h1>
                <p class="school-motto">Unggul dalam Prestasi, Berkarakter, dan Berbudaya</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-4">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <button class="btn password-toggle" type="button" onclick="togglePassword()">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>MASUK KE SISTEM
                </button>
            </form>
        </div>
    </div>

<script>
function togglePassword(){
    const passwordInput = document.getElementById('password');
    const icon = event.currentTarget.querySelector('i');
    if(passwordInput.type === 'password'){
        passwordInput.type = 'text';
        icon.classList.replace('fa-eye','fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('fa-eye-slash','fa-eye');
    }
}
</script>
</body>
</html>