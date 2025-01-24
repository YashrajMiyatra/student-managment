<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
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
        $target_dir = "uploads/";
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    // Insert into the database
    $sql = "INSERT INTO students (first_name, last_name, roll_number, department, address, phone_number, email, image_path) 
            VALUES (:first_name, :last_name, :roll_number, :department, :address, :phone_number, :email, :image_path)";
    
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':roll_number', $roll_number);
    $stmt->bindParam(':department', $department);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone_number', $phone_number);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':image_path', $image_path);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Student added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Failed to add student.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Student Data Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

      <script>
        function validateForm() {
            var firstName = document.getElementById('first_name').value.trim();
            var lastName = document.getElementById('last_name').value.trim();
            var email = document.getElementById('email').value.trim();
            var phone = document.getElementById('phone').value.trim();
            var address = document.getElementById('address').value.trim();
            var image = document.getElementById('image').files.length;

            if (!firstName || !lastName || !email || !phone || !address || image === 0) {
                alert('Please fill out all fields and upload an image.');
                return false;
            }

            // Basic email format validation
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address.');
                return false;
            }

            // Phone number validation (must be exactly 10 digits)
            var phonePattern = /^[0-9]{10}$/;
            if (!phonePattern.test(phone)) {
                alert('Phone number must be exactly 10 digits.');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Student Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="students.php">Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_student.php">Add Student</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Add Student</h2>
<form action="add_student.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" required>
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" required>
    </div>
    <div class="mb-3">
        <label for="roll_number" class="form-label">Roll Number</label>
        <input type="text" class="form-control" id="roll_number" name="roll_number" required>
    </div>
    <div class="mb-3">
        <label for="department" class="form-label">Department</label>
        <input type="text" class="form-control" id="department" name="department">
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="phone_number" class="form-label">Phone Number</label>
        <input type="text" class="form-control" id="phone_number" name="phone_number">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Upload Image</label>
        <input type="file" class="form-control" id="image" name="image">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

