<?php
require_once '../helper/db_helper.php';
session_start();

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Obține ID-ul utilizatorului curent
$user_id = $_SESSION['user_id'];

// Interogare pentru cursurile la care utilizatorul nu este înscris
$query = "
    SELECT c.id, c.name, c.credits, u.nume AS professor_name, u.prenume AS professor_surname
    FROM courses c
    LEFT JOIN users u ON c.id_professor = u.id
    WHERE c.id NOT IN (
        SELECT course_id 
        FROM enrollments 
        WHERE user_id = ?
    )
";
$params = [$user_id];
$courses = db_select($query, $params);

?>



<?php
require_once '../analytics/analytics.php';
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursuri Disponibile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Cursuri Disponibile</h1>
        <?php if (!empty($courses)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nume Curs</th>
                        <th>Credite</th>
                        <th>Profesor</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['name']); ?></td>
                            <td><?php echo htmlspecialchars($course['credits']); ?></td>
                            <td><?php echo htmlspecialchars($course['professor_name'] . ' ' . $course['professor_surname']); ?></td>
                            <td>
                                <form method="POST" action="enroll-course.php">
                                    <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['id']); ?>">
                                    <button type="submit" class="btn btn-primary btn-sm">Înscrie-te</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">Nu există cursuri disponibile pentru înscriere.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
