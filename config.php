<?php
$host = "localhost"; // Thay bằng thông tin của bạn
$username = "root"; // Username MySQL
$password = ""; // Mật khẩu MySQL
$database = "todo_list"; // Tên database

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
