<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"]) && isset($_POST["note"])) {
    $id = $_POST["id"];
    $note = $_POST["note"];

    $stmt = $conn->prepare("UPDATE tasks SET notes = ? WHERE id = ?");
    $stmt->bind_param("si", $note, $id);
    $stmt->execute();
    $stmt->close();
    
    echo "Ghi chú đã được cập nhật!";
}
?>
