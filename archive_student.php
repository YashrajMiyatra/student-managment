<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("UPDATE students SET archived = 1 WHERE id = ?");
    $stmt->execute([$id]);
    
    header("Location: students.php");
    exit();
}
?>
