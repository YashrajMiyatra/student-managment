<?php
include 'config.php';

// Define records per page
$recordsPerPage = 8;

// Get search parameters
$searchFirstName = isset($_GET['search_first_name']) ? trim($_GET['search_first_name']) : '';
$searchLastName = isset($_GET['search_last_name']) ? trim($_GET['search_last_name']) : '';
$searchRollNumber = isset($_GET['search_roll_number']) ? trim($_GET['search_roll_number']) : '';
$searchDepartment = isset($_GET['search_department']) ? trim($_GET['search_department']) : '';

// Prepare SQL query for counting total archived records
$searchConditions = [];
$params = [];

if ($searchFirstName) {
    $searchConditions[] = 'first_name LIKE :search_first_name';
    $params[':search_first_name'] = "%$searchFirstName%";
}
if ($searchLastName) {
    $searchConditions[] = 'last_name LIKE :search_last_name';
    $params[':search_last_name'] = "%$searchLastName%";
}
if ($searchRollNumber) {
    $searchConditions[] = 'roll_number LIKE :search_roll_number';
    $params[':search_roll_number'] = "%$searchRollNumber%";
}
if ($searchDepartment) {
    $searchConditions[] = 'department LIKE :search_department';
    $params[':search_department'] = "%$searchDepartment%";
}

$searchSql = implode(' AND ', $searchConditions);
if ($searchSql) {
    $searchSql = "WHERE archived = 1 AND $searchSql";
} else {
    $searchSql = "WHERE archived = 1";
}

$totalRecordsSql = "SELECT COUNT(*) AS total FROM students $searchSql";
$totalRecordsStmt = $pdo->prepare($totalRecordsSql);
$totalRecordsStmt->execute($params);
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

// Prepare SQL query for archived students table
$sql = "SELECT * FROM students $searchSql ORDER BY $sortColumn $sortOrder LIMIT :offset, :limit";
$stmt = $pdo->prepare($sql);

// Bind parameters
foreach ($params as $key => $param) {
    $stmt->bindValue($key, $param);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Students</title>
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
        function unarchiveStudent(id) {
            if (confirm("Are you sure you want to unarchive this student?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "unarchive_student.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert("Student unarchived successfully!");
                        location.reload();
                    }
                };
                xhr.send("id=" + id);
            }
        }

        function deleteStudent(id) {
            if (confirm("Are you sure you want to delete this student permanently?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_student.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert("Student deleted permanently!");
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
    <h2>Archived Students</h2>
    
    <!-- Search Form -->
<form method="GET" class="mb-4">
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
            <a href="archived_student.php" class="btn btn-secondary w-100">Reset</a>
        </div>
    </div>
</form>


    


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Picture</th>
                <th><a href="?sort=first_name&order=<?php echo ($sortColumn == 'first_name' && $sortOrder == 'ASC') ? 'desc' : 'asc'; ?>&search_first_name=<?php echo htmlspecialchars($searchFirstName); ?>&search_last_name=<?php echo htmlspecialchars($searchLastName); ?>&search_roll_number=<?php echo htmlspecialchars($searchRollNumber); ?>&search_department=<?php echo htmlspecialchars($searchDepartment); ?>">First Name</a></th>
                <th><a href="?sort=last_name&order=<?php echo ($sortColumn == 'last_name' && $sortOrder == 'ASC') ? 'desc' : 'asc'; ?>&search_first_name=<?php echo htmlspecialchars($searchFirstName); ?>&search_last_name=<?php echo htmlspecialchars($searchLastName); ?>&search_roll_number=<?php echo htmlspecialchars($searchRollNumber); ?>&search_department=<?php echo htmlspecialchars($searchDepartment); ?>">Last Name</a></th>
                <th><a href="?sort=roll_number&order=<?php echo ($sortColumn == 'roll_number' && $sortOrder == 'ASC') ? 'desc' : 'asc'; ?>&search_first_name=<?php echo htmlspecialchars($searchFirstName); ?>&search_last_name=<?php echo htmlspecialchars($searchLastName); ?>&search_roll_number=<?php echo htmlspecialchars($searchRollNumber); ?>&search_department=<?php echo htmlspecialchars($searchDepartment); ?>">Roll Number</a></th>
                <th><a href="?sort=department&order=<?php echo ($sortColumn == 'department' && $sortOrder == 'ASC') ? 'desc' : 'asc'; ?>&search_first_name=<?php echo htmlspecialchars($searchFirstName); ?>&search_last_name=<?php echo htmlspecialchars($searchLastName); ?>&search_roll_number=<?php echo htmlspecialchars($searchRollNumber); ?>&search_department=<?php echo htmlspecialchars($searchDepartment); ?>">Department</a></th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td class="text-center">
                        <?php // Handle picture display
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
                        <button onclick="unarchiveStudent(<?php echo $student['id']; ?>)" class="btn btn-success btn-sm btn-action mb-1 w-100">Unarchive</button>
                        <button onclick="deleteStudent(<?php echo $student['id']; ?>)" class="btn btn-danger btn-sm btn-action w-100">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
