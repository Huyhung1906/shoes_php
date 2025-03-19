<?php
session_start();
require('../config.php'); // Kết nối database

$error_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';

    if (!empty($username)) {
        // Kiểm tra tài khoản có tồn tại không
        $stmt = $conn->prepare("SELECT id_user FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Tạo mật khẩu mới ngẫu nhiên
            $new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Cập nhật mật khẩu mới vào database
            $stmt = $conn->prepare("UPDATE user SET passwordhash = :password WHERE username = :username");
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':username', $username);

            if ($stmt->execute()) {
                $success_msg = "Mật khẩu mới của bạn là: <strong>$new_password</strong>. Hãy đăng nhập và đổi mật khẩu ngay!";
            } else {
                $error_msg = "Có lỗi xảy ra, vui lòng thử lại!";
            }
        } else {
            $error_msg = "Tên đăng nhập không tồn tại!";
        }
    } else {
        $error_msg = "Vui lòng nhập tên đăng nhập!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .forgot-container {
            max-width: 450px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .forgot-container:hover {
            transform: translateY(-5px);
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        }

        .btn-reset {
            background: #667eea;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-reset:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 12px;
        }

        .text-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .text-link:hover {
            color: #5a67d8;
            text-decoration: underline;
        }

        h3 {
            color: #333;
            font-weight: 700;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <h3 class="text-center">Quên mật khẩu</h3>
        <?php if (!empty($error_msg)): ?>
            <div class="alert alert-danger text-center"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <?php if (!empty($success_msg)): ?>
            <div class="alert alert-success text-center"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <button type="submit" class="btn btn-reset w-100 text-white">Lấy lại mật khẩu</button>
        </form>
        <div class="text-center mt-4">
            <a href="../login.php" class="text-link">Quay lại đăng nhập</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>