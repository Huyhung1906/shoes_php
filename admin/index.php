<?php
session_start();
include '../config.php';

if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] != 1) {
    header("Location: ../login.php");
    exit();
}

// Lấy tổng số khách hàng
$query = $conn->prepare("SELECT COUNT(*) as total FROM user");
$query->execute();
$customers = $query->fetch(PDO::FETCH_ASSOC)['total'];

// Lấy tổng số đơn hàng
$query = $conn->prepare("SELECT COUNT(*) as total FROM invoice");
$query->execute();
$orders = $query->fetch(PDO::FETCH_ASSOC)['total'];

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Quản Lý Bán Giày</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background-color: #f4f4f9; }
        .container { display: flex; min-height: 100vh; }
        .sidebar { width: 80px; background-color: #1a1f37; color: white; padding: 20px 0; text-align: center; }
        .sidebar a { color: #a3a6b4; display: block; padding: 15px; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { color: white; background-color: #2c3149; }
        .main-content { flex-grow: 1; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 10px 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
        .stats .card { background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stats .card h3 { font-size: 14px; color: #6c757d; margin-bottom: 10px; }
        .stats .card p { font-size: 24px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #1a1f37; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <a href="index.php" class="active"><i class="fas fa-tachometer-alt"></i></a>
            <a href="users.php"><i class="fas fa-users"></i></a>
            <a href="products.php"><i class="fas fa-shoe-prints"></i></a>
            <a href="invoices.php"><i class="fas fa-file-invoice"></i></a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i></a>
        </div>
        <div class="main-content">
            <div class="header">
                <h2>Dashboard</h2>
            </div>
            <div class="stats">
                <div class="card">
                    <h3>Khách hàng</h3>
                    <p><?php echo $customers; ?></p>
                </div>
                <div class="card">
                    <h3>Đơn hàng</h3>
                    <p><?php echo $orders; ?></p>
                </div>
                <div class="card">
                    <h3>Doanh thu</h3>
                    <p><?php echo number_format($revenue, 0, ',', '.'); ?> VNĐ</p>
                </div>
            </div>
            <div class="card">
                <h3>Sản phẩm bán chạy</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng bán</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_products as $row) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</td>
                                <td><?php echo $row['quantity']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>