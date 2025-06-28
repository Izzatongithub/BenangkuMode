<?php
session_start();
require_once 'config/database.php';

// Redirect if already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = mysqli_real_escape_string($conn, $_POST['full_name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $password = mysqli_real_escape_string($conn, $_POST['password'] ?? '');
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password'] ?? '');
    $agreeTerms = isset($_POST['agree_terms']);
    
    // Validation
    if (empty($fullName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Semua field wajib diisi';
    } elseif (!validateEmail($email)) {
        $error = 'Format email tidak valid';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter';
    } elseif ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak cocok';
    } elseif (!$agreeTerms) {
        $error = 'Anda harus menyetujui syarat dan ketentuan';
    } else {
        try {
            // Check if email already exists
            $sql = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
            $result = mysqli_query($conn, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $error = 'Email sudah terdaftar. Silakan gunakan email lain atau login.';
            } else {
                // Create new user
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $username = strtolower(str_replace(' ', '', $fullName)) . '_' . rand(100, 999);
                
                $sql_insert = "INSERT INTO users (username, email, password, full_name, phone, address, role) VALUES ('$username', '$email', '$hashedPassword', '$fullName', '$phone', '$address', 'customer')";
                if (mysqli_query($conn, $sql_insert)) {
                    $success = 'Registrasi berhasil! Silakan login dengan akun Anda.';
                    
                    // Log activity
                    logActivity('registration', 'New user registered: ' . $email);
                    
                    // Clear form data
                    $_POST = array();
                } else {
                    $error = 'Gagal membuat akun. Silakan coba lagi.';
                }
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
    <title>Registrasi - BenangkuMode</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .register-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .register-body {
            padding: 40px 30px;
        }
        .form-floating {
            margin-bottom: 20px;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }
        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .form-check {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <i class="fas fa-user-plus fa-3x mb-3"></i>
                <h3>Buat Akun Baru</h3>
                <p class="mb-0">Bergabunglah dengan komunitas BenangkuMode</p>
            </div>
            
            <div class="register-body">
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
                
                <form method="POST" action="" id="registerForm">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nama Lengkap" required value="<?php echo $_POST['full_name'] ?? ''; ?>">
                        <label for="full_name"><i class="fas fa-user me-2"></i>Nama Lengkap</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value="<?php echo $_POST['email'] ?? ''; ?>">
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Nomor Telepon" value="<?php echo $_POST['phone'] ?? ''; ?>">
                        <label for="phone"><i class="fas fa-phone me-2"></i>Nomor Telepon (Opsional)</label>
                    </div>
                    
                    <div class="form-floating">
                        <textarea class="form-control" id="address" name="address" placeholder="Alamat" style="height: 100px"><?php echo $_POST['address'] ?? ''; ?></textarea>
                        <label for="address"><i class="fas fa-map-marker-alt me-2"></i>Alamat (Opsional)</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        <div class="password-strength" id="passwordStrength"></div>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password" required>
                        <label for="confirm_password"><i class="fas fa-lock me-2"></i>Konfirmasi Password</label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                        <label class="form-check-label" for="agree_terms">
                            Saya setuju dengan <a href="#" class="text-decoration-none">Syarat dan Ketentuan</a> serta <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                        <label class="form-check-label" for="newsletter">
                            Saya ingin menerima newsletter dan informasi terbaru dari BenangkuMode
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-register">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </button>
                </form>
                
                <div class="login-link">
                    <p class="mb-0">Sudah punya akun? <a href="login.php">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
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
        
        // Password confirmation checker
        document.getElementById('confirm_password').addEventListener('input', function() {
            var password = document.getElementById('password').value;
            var confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html> 