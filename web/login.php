<?php
session_start();
require_once '../config/koneksi.php';

// Di login.php, perbaiki bagian redirect berdasarkan role:
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: dosen/dashboard.php");
    } else if ($_SESSION['role'] == 'mahasiswa') {
        header("Location: mhs/home-mhs.php");
    }
    exit();
}

// Proses login jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    
    $stmt = mysqli_prepare($koneksi, "SELECT id, username, password, nama, role FROM admin_users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Verifikasi password (password default adalah 'password' yang sudah di-hash)
        // Di login.php, setelah berhasil login
if (password_verify($password, $row['password'])) {
    // Set session
    $_SESSION['id'] = $row['id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];
    
    // Jika role mahasiswa, ambil data dari tabel mahasiswa
    if ($row['role'] == 'mahasiswa') {
        $query_mhs = mysqli_query($koneksi, "SELECT nama FROM mahasiswa WHERE nim = '{$row['username']}'");
        if ($mhs = mysqli_fetch_assoc($query_mhs)) {
            $_SESSION['nama'] = $mhs['nama'];
        } else {
            $_SESSION['nama'] = $row['nama'];
        }
    } else {
        $_SESSION['nama'] = $row['nama'];
    }
    
    // Redirect berdasarkan role
    if ($row['role'] == 'admin') {
        header("Location: dosen/dashboard.php");
    } else {
        header("Location: mhs/home-mhs.php");
    }
    exit();
}
    }
    
    $error = "Username atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SAPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            background-image: url('/SAPA-V2/assets/img/polibatam.jpeg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background-color: #f3f3f3ff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }

        .logo { 
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100px;
        }
        
        .login-header {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        
        .login-title {
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .form-control {
            margin-bottom: 15px;
        }
        .error-message {
            color: red;
            font-size: 14px;
            display: none;
        }
        .btn-login {
            width: 100%;
            margin-top: 10px;
        }
        
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .alert {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        
        <img src="/SAPA-V2/assets/img/logosapa1.png" alt="Logo" class="logo mb-3">
        
        <div class="login-header">
            <h2 class="login-title">LOGIN</h2>
        </div>
        
        <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <form id="loginForm" method="POST" action="">
            <div class="mb-3">
                <label for="userId" class="form-label">Username</label>
                <input type="text" class="form-control" id="userId" name="username" placeholder="Masukkan username Anda" required>
                <div class="error-message" id="usernameError">Username tidak boleh kosong</div>
            </div>
            
            <div class="mb-3">
                <label for="userPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="userPassword" name="password" placeholder="Masukkan password Anda" required>
                <div class="error-message" id="passwordError">Password tidak boleh kosong</div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-login">Login</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validasi form client-side
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            let valid = true;
            const username = document.getElementById('userId');
            const password = document.getElementById('userPassword');
            const usernameError = document.getElementById('usernameError');
            const passwordError = document.getElementById('passwordError');
            
            // Reset error messages
            usernameError.style.display = 'none';
            passwordError.style.display = 'none';
            
            if (username.value.trim() === '') {
                usernameError.style.display = 'block';
                valid = false;
            }
            
            if (password.value.trim() === '') {
                passwordError.style.display = 'block';
                valid = false;
            }
            
            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>