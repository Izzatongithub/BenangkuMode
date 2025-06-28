<?php
session_start();
require_once 'config/database.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';
$token = $_GET['token'] ?? '';
$validToken = false;
$userEmail = '';

// Validate token
if (!empty($token)) {
    try {
        $user = dbFetchOne(
            "SELECT id, email, reset_expiry FROM users WHERE reset_token = ? AND reset_expiry > NOW() AND is_active = 1",
            array($token)
        );
        
        if ($user) {
            $validToken = true;
            $userEmail = $user['email'];
        } else {
            $error = 'Token reset password tidak valid atau sudah kadaluarsa.';
        }
    } catch (Exception $e) {
        $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    }
} else {
    $error = 'Token reset password tidak ditemukan.';
}

// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    if (empty($password) || empty($confirm_password)) {
        $error = 'Password dan konfirmasi password harus diisi';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok';
    } else {
        try {
            // Update password and clear reset token
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET password = '$hashed_password', reset_token = NULL, reset_expiry = NULL WHERE email = '$userEmail'";
            if (mysqli_query($conn, $sql_update)) {
                $success = 'Password berhasil diubah! Silakan login dengan password baru Anda.';
                
                // Log activity
                logActivity('password_reset', 'Password reset successful for: ' . $userEmail);
                
                // Clear token from URL
                $token = '';
            } else {
                $error = 'Gagal mengubah password. Silakan coba lagi.';
            }
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BenangkuMode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .reset-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .reset-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .reset-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .reset-body {
            padding: 40px 30px;
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .btn-reset {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-card">
            <div class="reset-header">
                <i class="fas fa-key fa-3x mb-3"></i>
                <h3>Reset Password</h3>
                <p class="mb-0">Masukkan password baru Anda</p>
            </div>
            
            <div class="reset-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($validToken && !$success): ?>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        Reset password untuk: <strong><?php echo $userEmail; ?></strong>
                    </div>
                    
                    <form method="POST" action="" id="resetForm">
                        <div class="form-floating">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password Baru" required>
                            <label for="password"><i class="fas fa-lock me-2"></i>Password Baru</label>
                            <div class="password-strength" id="passwordStrength"></div>
                        </div>
                        
                        <div class="form-floating">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password" required>
                            <label for="confirm_password"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-reset">
                            <i class="fas fa-save me-2"></i>Simpan Password Baru
                        </button>
                    </form>
                <?php endif; ?>
                
                <div class="back-link">
                    <p class="mb-0">
                        <a href="login.php"><i class="fas fa-arrow-left me-2"></i>Kembali ke Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength checker
        var passwordElement = document.getElementById('password');
        if (passwordElement) {
            passwordElement.addEventListener('input', function() {
                var password = this.value;
                var strengthDiv = document.getElementById('passwordStrength');
                
                var strength = 0;
                var message = '';
                var className = '';
                
                if (password.length >= 6) strength++;
                if (password.match(/[a-z]/)) strength++;
                if (password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;
                
                if (strength < 2) {
                    message = 'Lemah';
                    className = 'strength-weak';
                } else if (strength < 4) {
                    message = 'Sedang';
                    className = 'strength-medium';
                } else {
                    message = 'Kuat';
                    className = 'strength-strong';
                }
                
                strengthDiv.textContent = 'Kekuatan password: ' + message;
                strengthDiv.className = 'password-strength ' + className;
            });
        }
        
        // Password confirmation checker
        var confirmPasswordElement = document.getElementById('confirm_password');
        if (confirmPasswordElement) {
            confirmPasswordElement.addEventListener('input', function() {
                var password = document.getElementById('password').value;
                var confirmPassword = this.value;
                
                if (confirmPassword && password !== confirmPassword) {
                    this.setCustomValidity('Password tidak cocok');
                } else {
                    this.setCustomValidity('');
                }
            });
        }
    </script>
</body>
</html> 