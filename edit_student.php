<?php
include 'config.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <h2>Edit Student</h2>
    <form action="update_student.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="roll_number" class="form-label">Roll Number</label>
            <input type="text" class="form-control" id="roll_number" name="roll_number" value="<?php echo htmlspecialchars($student['roll_number']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($student['department']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($student['address']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Upload New Image</label>
            <input type="file" class="form-control" id="image" name="image">
            <?php if ($student['image_path']): ?>
                <img src="images/<?php echo htmlspecialchars($student['image_path']); ?>" alt="Student Image" class="img-thumbnail mt-2" width="100">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
