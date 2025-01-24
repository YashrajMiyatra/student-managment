<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $studentId = (int)$_POST['id'];

        // Delete the student from the database
        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $studentId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo 'Student deleted permanently!';
        } else {
            echo 'Error deleting student.';
        }
    } else {
        echo 'No student ID provided.';
    }
} else {
    echo 'Invalid request method.';
}
?>
