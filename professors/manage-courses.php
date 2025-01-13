<?php

require_once '../helper/db_helper.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['idRole'] !== 1) {
    header("Location: access_denied.php");
    exit;
}

// Include fișierul de conexiune la baza de date
require_once '../db_connection.php';

// Preia ID-ul utilizatorului din sesiune
$user_id = $_SESSION['user_id'];

// Query pentru a selecta materiile la care este înscris utilizatorul
// Query pentru a prelua materiile asociate profesorului
$query = "SELECT c.id, c.name, c.credits , c.id_professor FROM courses c  WHERE c.id_professor = $user_id";

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
    <title>Materiile mele</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">Cursurile mele</h1>
        <?php if (empty($courses)): ?>
            <p>Nu aveti niciun curs selectat.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($course['name']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">Credite: <?php echo htmlspecialchars($course['credits']); ?></h6>
                            </div>
                            <a href="view-courseStudents.php?id=<?php echo $course['id']; ?>" class="btn btn-primary">View Students</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
