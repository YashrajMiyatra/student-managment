<?php
include 'config.php';

// Function to insert a student record
function insert_student($name, $roll_number, $class, $address, $phone_number, $email, $image_path) {
    $conn = db_connect();
    $query = "INSERT INTO students (name, roll_number, class, address, phone_number, email, image_path)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sssssss', $name, $roll_number, $class, $address, $phone_number, $email, $image_path);
    
    if (mysqli_stmt_execute($stmt)) {
        return true;
    } else {
        return false;
    }
}


?>
