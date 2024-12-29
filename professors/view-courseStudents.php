<?php

require_once '../helper/db_helper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Verifică dacă ID-ul cursului a fost trimis prin GET
if (!isset($_GET['id'])) {
    die("ID-ul cursului nu a fost specificat.");
}

$course_id = $_GET['id'];

// Query pentru a obține detaliile cursului
$course_query = "SELECT name, credits FROM courses WHERE id = ?";
$course_details = db_select($course_query, [$course_id])[0];

// Query pentru a obține studenții și notele lor asociate cursului
$students_query = "
    SELECT u.id AS student_id, u.nume AS student_name, u.prenume AS student_firstname, e.grade, e.grade_date , e.id as enrollment_id, e.course_id ,c.id ,  c.name as course_name
    FROM enrollments e
    INNER JOIN users u ON e.user_id = u.id
    INNER JOIN courses c ON e.course_id = c.id
    WHERE e.course_id = ?
";
$students = db_select($students_query, [$course_id]);
?>


<?php
require_once '../analytics/analytics.php';
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalii Curs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4">Detalii Curs</h1>

        <!-- Afișare detalii curs -->
        <div class="mb-4">
            <h3><?php echo htmlspecialchars($course_details['name']); ?></h3>
            <p><strong>Credite:</strong> <?php echo htmlspecialchars($course_details['credits']); ?></p>
        </div>

        <!-- Afișare lista studenților -->
        <h4>Studenți înscriși</h4>
        <?php if (empty($students)): ?>
            <p>Nu există studenți înscriși la acest curs.</p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nume</th>
                        <th>Prenume</th>
                        <th>Notă</th>
                        <th>Data notarii</th>
                        <th>Actiuni</th>


                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $index => $student): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['student_firstname']); ?></td>
                            <td><?php echo htmlspecialchars($student['grade'] ?? 'Notă lipsă'); ?></td>
                            <td><?php echo htmlspecialchars($student['grade_date'] ?? 'Data lipsă'); ?></td>
                            <td><a href="add-grade.php?enrollmentId=<?php echo $student['enrollment_id']; ?> &studentName=<?php echo $student['student_name']; ?> &courseName=<?php echo $student['course_name']; ?>" class="btn btn-primary"><?php echo $student['grade'] ? 'Modifica Nota' : 'Adauga Nota'; ?></a></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="./export_excel.php?id=<?php echo $course_id; ?>" class="btn btn-success mb-3">Exportă în Excel</a>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
