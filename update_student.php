<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $rollNumber = $_POST['roll_number'];
    $department = $_POST['department'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phone_number'];
    $email = $_POST['email'];

    // Handle image upload
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imagePath = 'images/' . basename($imageName);
        move_uploaded_file($imageTmpName, $imagePath);
    }

    $sql = "UPDATE students SET first_name = :first_name, last_name = :last_name, roll_number = :roll_number, department = :department, address = :address, phone_number = :phone_number, email = :email";
    
    if ($imagePath) {
        $sql .= ", image_path = :image_path";
    }
    
    $sql .= " WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':first_name', $firstName);
    $stmt->bindParam(':last_name', $lastName);
    $stmt->bindParam(':roll_number', $rollNumber);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone_number', $phoneNumber);
    $stmt->bindParam(':email', $email);
    if ($imagePath) {
        $stmt->bindParam(':image_path', $imagePath);
    }
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header('Location: students.php');
    } else {
        echo "Failed to update student.";
    }
}
?>
