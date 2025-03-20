<?php
session_start();
include '../config.php';

if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Xử lý tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$queryStr = "SELECT * FROM user";
if ($search) {
    $queryStr .= " WHERE username LIKE :search OR email LIKE :search";
}
$query = $conn->prepare($queryStr);
if ($search) {
    $query->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$query->execute();
$users = $query->fetchAll(PDO::FETCH_ASSOC);

// Xử lý xóa user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = $conn->prepare("DELETE FROM user WHERE id_user = ?");
    $deleteQuery->execute([$id]);
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f4f9; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 50px; background-color: #1a1f37; color: white; padding: 20px 0; text-align: center; }
        .sidebar a { color: #a3a6b4; display: block; padding: 15px; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { color: white; background-color: #2c3149; }
        .main-content { flex-grow: 1; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 10px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .search-bar input { padding: 5px; width: 200px; }
        .table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .table th, .table td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        .table th { background: #1a1f37; color: white; }
        .actions a { margin: 0 5px; text-decoration: none; color: #333; }
        .actions a.delete { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <a href="index.php"><i class="fas fa-tachometer-alt"></i></a>
            <a href="users.php" class="active"><i class="fas fa-users"></i></a>
            <a href="products.php"><i class="fas fa-shoe-prints"></i></a>
            <a href="invoices.php"><i class="fas fa-file-invoice"></i></a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i></a>
        </div>

        <div class="main-content">
            <div class="header">
                <form method="GET" class="search-bar">
                    <input type="text" name="search" placeholder="Tìm kiếm user..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Tìm</button>
                </form>
                <a href="add_user.php">+ Thêm User</a>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <td><?php echo $user['id_user']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo ($user['id_role'] == 1) ? 'Admin' : 'User'; ?></td>
                            <td class="actions">
                                <a href="edit_user.php?id=<?php echo $user['id_user']; ?>"><i class="fas fa-edit"></i></a>
                                <a href="users.php?delete=<?php echo $user['id_user']; ?>" class="delete" onclick="return confirm('Xóa user này?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
