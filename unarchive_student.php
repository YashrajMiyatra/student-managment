<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("UPDATE students SET archived = 0 WHERE id = ?");
    $stmt->execute([$id]);
    
    header("Location: archived_student.php");
    exit();
}
?>
