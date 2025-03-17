<?php
session_start();
if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] != 1) {
    header("Location: login.php");
    exit();
}
echo "<h1>Xin chào, ID của bạn là: " . $_SESSION['id_user'] . "</h1>";

?>
<h1>Admin Dashboard</h1>
<a href="../logout.php">Đăng xuất</a>

