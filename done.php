<?php
include "config.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $stmt = $conn->prepare("UPDATE tasks SET status = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit();
?>
