<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $roll_number = $_POST['roll_number'] ?? null;
    $department = $_POST['department'] ?? null;
    $address = $_POST['address'] ?? null;
    $phone_number = $_POST['phone_number'] ?? null;
    $email = $_POST['email'] ?? null;

    // Handle file upload
    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "images/";
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    } else {
        // Keep existing image if no new image is uploaded
        $stmt = $pdo->prepare("SELECT image_path FROM students WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        $image_path = $student['image_path'];
    }

    // Update the database
    $sql = "UPDATE students SET first_name = :first_name, last_name = :last_name, roll_number = :roll_number, department = :department, address = :address, phone_number = :phone_number, email = :email, image_path = :image_path WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':roll_number', $roll_number);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':image_path', $image_path);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Student updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Failed to update student.</div>";
    }
}
?>
