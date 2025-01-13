
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

        if ($_SESSION['idRole'] !== 2) {
            header("Location: access_denied.php");
            exit;
        }

        // Obține ID-ul utilizatorului curent
        $user_id = $_SESSION['user_id'];

        // Interogare pentru a obține cursurile înscrise de utilizator
        $query = "
            SELECT c.id AS course_id, c.name AS course_name, c.credits, e.enrolled_at, e.grade as grade, e.grade_date
            FROM enrollments e
            INNER JOIN courses c ON e.course_id = c.id
            WHERE e.user_id = ?
        ";
        $courses = db_select($query, [$user_id]);
        ?>
        <?php if (empty($courses)): ?>
            <p>Nu sunteți înscris la nicio materie.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted">Credite: <?php echo htmlspecialchars($course['credits']); ?></h6>
                                <p class="card-text">
                                    Data înscrierii: <?php echo htmlspecialchars($course['enrolled_at']); ?>
                                </p>
                                <?php if (!empty($course['grade'])): ?>
                                    <p class="card-text">
                                        Nota: <?php echo htmlspecialchars($course['grade']); ?>
                                    </p>
                                    <p class="card-text">
                                        Data notarii: <?php echo htmlspecialchars($course['grade_date']); ?>
                                    </p>
                                <?php endif; ?>    
                                <!-- Buton Leave Course -->

                                <?php if (empty($course['grade'])): ?>
                                    <form method="POST" action="leave-course.php" onsubmit="return confirm('Sigur doriți să părăsiți acest curs?');">
                                        <input type="hidden" name="course_id" value="<?php echo htmlspecialchars($course['course_id']); ?>">
                                        <button type="submit" class="btn btn-danger">Părăsește cursul</button>
                                    </form>
                                <?php endif; ?>    
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
