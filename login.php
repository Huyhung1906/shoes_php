
<?php
session_start();
require('config.php'); // Kết nối database

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id_user, username, passwordhash, id_role, is_active FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['is_active'] == 0) {
                $error_msg = "Tài khoản của bạn đã bị khóa!";
            } elseif ($password === $user['passwordhash']) { // Nếu mật khẩu chưa được mã hóa
                $_SESSION['username'] = $user['username'];
                $_SESSION['id_role'] = $user['id_role'];
                $_SESSION['id_user'] = $user['id_user'];
                
                if ($user['id_role'] == 1) {
                    $_SESSION['admin_logged_in'] = true;
                    header("Location: admin/index.php");
                } else {
                    header("Location: user/index.php");
                }
                exit();
            } else {
                $error_msg = "Sai tài khoản hoặc mật khẩu!";
            }
        } else {
            $error_msg = "Tài khoản không tồn tại!";
        }
    } else {
        $error_msg = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus { box-shadow: none; border-color: #007bff; }
        .btn-login { background: #007bff; border: none; }
        .btn-login:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="login-container">
    <h3 class="text-center">Đăng nhập</h3>
    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger text-center"> <?php echo $error_msg; ?> </div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Tên đăng nhập</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-login w-100 text-white">Đăng nhập</button>
    </form>
    <div class="text-center mt-3">
        <a href="#">Quên mật khẩu?</a>
    </div>
</div>
</body>
</html>
