<?php
// Pornește sesiunea doar dacă nu este deja activă
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit;
}

// Debug pentru idRole
if (isset($_SESSION['idRole'])) {
    echo "<pre>idRole din sesiune: " . $_SESSION['idRole'] . "</pre>";
} else {
    echo "<pre>idRole nu este setat în sesiune!</pre>";
}

$username = $_SESSION['username'];
$idRole = $_SESSION['idRole'];
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">MyApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                <?php if ($idRole == 3): // Admin ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/admin-dashboard.php">Admin Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/manage-users.php">Manage Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/add-professor.php">Add Professor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/add-course.php">Add Course</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/manage-courses.php">Manage Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../analytics/view_analytics.php">View Analitics</a>
                        </li>
                    <?php elseif ($idRole == 2): // Student ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../students/student-dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../students/student-courses.php">My Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../students/attend-courses.php">Attend Courses</a>
                        </li>
                    <?php elseif ($idRole == 1): // Profesor ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../professors/professorDashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../professors/manage-courses.php">Manage Courses</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">Hello, <?php echo htmlspecialchars($username); ?>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../profile/viewProfile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
