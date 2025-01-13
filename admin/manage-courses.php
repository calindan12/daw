<?php
require_once '../helper/db_helper.php';
session_start();

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

if ($_SESSION['idRole'] !== 3) {
    header("Location: access_denied.php");
    exit;
}
// Obține utilizatorii (exceptând adminii)
$query = "SELECT c.id, c.name, c.credits, c.id_professor, u.nume , u.prenume, u.id AS role FROM courses c LEFT JOIN users u ON u.id = c.id_professor
    ORDER BY c.name ";

$courses = db_select($query);
?>


<?php
require_once '../analytics/analytics.php';
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
            <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?php 
                echo htmlspecialchars($_SESSION['success_message']); 
                unset($_SESSION['success_message']); // Șterge mesajul după afișare
                ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo htmlspecialchars($_SESSION['error_message']); 
                unset($_SESSION['error_message']); // Șterge mesajul după afișare
                ?>
            </div>
        <?php endif; ?>
        <h1 class="mb-4 text-center">Manage Courses</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nume Curs</th>
                        <th>Credite</th>
                        <th>Profesor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($courses)): ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['name']); ?></td>
                                <td><?php echo htmlspecialchars($course['credits']); ?></td>
                                <td><?php echo htmlspecialchars($course['nume'] . ' ' . $course['prenume']); ?></td>
                                <td>
                                    <a href="edit-course.php?id=<?php echo $course['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete-course.php?id=<?php echo $course['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Course?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
