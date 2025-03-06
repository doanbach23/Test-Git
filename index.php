<?php
include "config.php"; // Kết nối database

// Thêm công việc mới
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task"])) {
    $task = $_POST["task"];
    $deadline = $_POST["deadline"] ?? NULL;
    $stmt = $conn->prepare("INSERT INTO tasks (task, deadline) VALUES (?, ?)");
    $stmt->bind_param("ss", $task, $deadline);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

// Lọc công việc (Chưa hoàn thành hoặc Đã hoàn thành)
$filter = isset($_GET["filter"]) ? $_GET["filter"] : "all";
$query = "SELECT * FROM tasks";
if ($filter === "pending") {
    $query .= " WHERE status = 0";
} elseif ($filter === "completed") {
    $query .= " WHERE status = 1";
}
$query .= " ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Công Việc</title>
    <!-- Sử dụng Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $(".note-input").on("change", function() {
        var taskId = $(this).data("id");
        var newNote = $(this).val();
        
        $.post("update_note.php", { id: taskId, note: newNote }, function(response) {
            alert(response); // Hiển thị thông báo ghi chú đã lưu
        });
    });
});
</script>


<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center text-primary">📌 Danh sách công việc</h2>

        <!-- Form thêm công việc -->
        <div class="card p-4 shadow-sm mt-3">
            <form method="POST" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="task" placeholder="Nhập công việc mới..." required>
                </div>
                <div class="col-md-4">
                    <input type="date" class="form-control" name="deadline">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">➕ Thêm</button>
                </div>
            </form>
        </div>

        <!-- Lọc công việc -->
        <div class="d-flex justify-content-center my-3">
            <a href="index.php?filter=all" class="btn btn-outline-dark mx-1">Tất cả</a>
            <a href="index.php?filter=pending" class="btn btn-outline-warning mx-1">⏳ Chưa hoàn thành</a>
            <a href="index.php?filter=completed" class="btn btn-outline-success mx-1">✅ Đã hoàn thành</a>
        </div>

        <!-- Hiển thị danh sách công việc -->
        <div class="card shadow-sm">
        <table class="table table-hover">
    <thead class="table-dark">
        <tr>
            <th scope="col">Công việc</th>
            <th scope="col">Hạn chót</th>
            <th scope="col">Trạng thái</th>
            <th scope="col">Ghi chú</th>
            <th scope="col" class="text-center">Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($row["task"]) ?></td>
                <td><?= $row["deadline"] ?: "Không có" ?></td>
                <td>
                    <?= $row["status"] ? "<span class='badge bg-success'>Hoàn thành</span>" : "<span class='badge bg-warning text-dark'>Chưa xong</span>" ?>
                </td>
                <td>
                    <input type="text" class="form-control note-input" data-id="<?= $row["id"] ?>" value="<?= htmlspecialchars($row["notes"] ?? '') ?>">
                </td>
                <td class="text-center">
                    <?php if (!$row["status"]) : ?>
                        <a class="btn btn-sm btn-success" href="done.php?id=<?= $row["id"] ?>">✔ Hoàn thành</a>
                    <?php endif; ?>
                    <a class="btn btn-sm btn-primary" href="edit.php?id=<?= $row["id"] ?>">✏ Chỉnh sửa</a>
                    <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $row["id"] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">❌ Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
