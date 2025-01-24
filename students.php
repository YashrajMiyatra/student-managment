<?php
include 'config.php';

// Define records per page
$recordsPerPage = 8; // Display 8 students per page

// Fetch total number of records
$totalRecordsStmt = $pdo->query("SELECT COUNT(*) AS total FROM students WHERE archived = 0");
$totalRecords = $totalRecordsStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get current page number
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));
$offset = ($currentPage - 1) * $recordsPerPage;

// Sorting
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sortOrder = isset($_GET['order']) ? strtoupper($_GET['order']) : 'ASC';
$sortOrder = $sortOrder == 'DESC' ? 'DESC' : 'ASC';

// Searching with individual fields
$searchFirstName = isset($_GET['search_first_name']) ? $_GET['search_first_name'] : '';
$searchLastName = isset($_GET['search_last_name']) ? $_GET['search_last_name'] : '';
$searchRollNumber = isset($_GET['search_roll_number']) ? $_GET['search_roll_number'] : '';
$searchDepartment = isset($_GET['search_department']) ? $_GET['search_department'] : '';

// Building the search query
$searchConditions = [];
if ($searchFirstName) {
    $searchConditions[] = "first_name LIKE :search_first_name";
}
if ($searchLastName) {
    $searchConditions[] = "last_name LIKE :search_last_name";
}
if ($searchRollNumber) {
    $searchConditions[] = "roll_number LIKE :search_roll_number";
}
if ($searchDepartment) {
    $searchConditions[] = "department LIKE :search_department";
}

$searchCondition = count($searchConditions) ? "AND " . implode(' AND ', $searchConditions) : '';

// Prepare SQL query for students table
$sql = "SELECT * FROM students WHERE archived = 0 $searchCondition ORDER BY $sortColumn $sortOrder LIMIT :offset, :limit";
$stmt = $pdo->prepare($sql);

if ($searchFirstName) {
    $stmt->bindValue(':search_first_name', "%$searchFirstName%", PDO::PARAM_STR);
}
if ($searchLastName) {
    $stmt->bindValue(':search_last_name', "%$searchLastName%", PDO::PARAM_STR);
}
if ($searchRollNumber) {
    $stmt->bindValue(':search_roll_number', "%$searchRollNumber%", PDO::PARAM_STR);
}
if ($searchDepartment) {
    $stmt->bindValue(':search_department', "%$searchDepartment%", PDO::PARAM_STR);
}
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':limit', $recordsPerPage, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .student-picture {
            width: 75px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table thead th {
            border-bottom: 2px solid #dee2e6; 
        }
        .btn-action {
            margin: 0 5px;
        }
    </style>
    <script>
        function archiveStudent(id) {
            if (confirm("Are you sure you want to archive this student?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "archive_student.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert("Student archived successfully!");
                        location.reload();
                    }
                };
                xhr.send("id=" + id);
            }
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
                <li class="nav-item">
                    <a class="nav-link" href="archived_student.php">Archived Students</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Student List</h2>
    
    <!-- Search Form -->
<form method="get" action="" class="mb-4">
    <div class="row g-2 align-items-center">

        <div class="col-12 text-center">
            <h3>Searching</h3>
        </div>

        <div class="col-lg-2 col-md-3 col-sm-6">
            <input type="text" name="search_first_name" class="form-control" placeholder="First Name" value="<?php echo htmlspecialchars($searchFirstName); ?>">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <input type="text" name="search_last_name" class="form-control" placeholder="Last Name" value="<?php echo htmlspecialchars($searchLastName); ?>">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <input type="text" name="search_roll_number" class="form-control" placeholder="Roll Number" value="<?php echo htmlspecialchars($searchRollNumber); ?>">
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6">
            <input type="text" name="search_department" class="form-control" placeholder="Department" value="<?php echo htmlspecialchars($searchDepartment); ?>">
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12 mt-sm-2 mt-md-0">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12 mt-sm-2 mt-md-0">
            <a href="students.php" class="btn btn-secondary w-100">Reset</a>
        </div>
    </div>
</form>

    
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Picture</th>
                <th><a href="?sort=first_name&order=<?php echo ($sortColumn == 'first_name' && $sortOrder == 'ASC') ? 'desc' : 'asc'; ?>">First Name</a></th>
                <th><a href="?sort=last_name&order=<?php echo ($sortColumn == 'last_name' && $sortOrder == 'ASC') ? 'desc' : 'asc'; ?>">Last Name</a></th>
                <th><a href="?sort=roll_number&order=<?php echo ($sortColumn == 'roll_number' && $sortOrder == 'ASC') ? 'desc' : 'asc'; ?>">Roll Number</a></th>
                <th><a href="?sort=department&order=<?php echo ($sortColumn == 'department' && $sortOrder == 'ASC') ? 'desc' : 'asc'; ?>">Department</a></th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td  class="text-center">
                        <?php
                        $picturePath = 'uploads/' . (isset($student['image_path']) ? basename($student['image_path']) : 'default-image.jpg');
                        ?>
                        <img src="<?php echo $picturePath; ?>" alt="Student Picture" class="img-fluid student-picture">

                    </td>
                    <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['roll_number']); ?></td>
                    <td><?php echo htmlspecialchars($student['department']); ?></td>
                    <td><?php echo htmlspecialchars($student['address']); ?></td>
                    <td><?php echo htmlspecialchars($student['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td class="text-center">
                        <a href="edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-warning btn-sm mb-1 w-100">Edit</a>
                        <button class="btn btn-danger btn-sm w-100" onclick="archiveStudent(<?php echo $student['id']; ?>)">Archive</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($currentPage > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">Previous</a></li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
            
            <?php if ($currentPage < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>

</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
