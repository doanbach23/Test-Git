<?php
include "config.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $result = $conn->query("SELECT * FROM tasks WHERE id = $id");
    $task = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $task_name = $_POST["task"];
    $deadline = $_POST["deadline"];
    $stmt = $conn->prepare("UPDATE tasks SET task = ?, deadline = ? WHERE id = ?");
    $stmt->bind_param("ssi", $task_name, $deadline, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chỉnh sửa công việc</title>
</head>
<body>
    <h2>Chỉnh sửa công việc</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $task["id"] ?>">
        <input type="text" name="task" value="<?= htmlspecialchars($task["task"]) ?>" required>
        <input type="date" name="deadline" value="<?= $task["deadline"] ?>">
        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>
