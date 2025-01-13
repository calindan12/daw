<?php

require_once '../helper/db_helper.php';

// require_once '../security/auth.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}


if ($_SESSION['idRole'] !== 1) {
    header("Location: access_denied.php");
    exit;
}

// Verifică dacă ID-ul cursului a fost trimis prin GET
if (!isset($_GET['enrollmentId'])) {
    die("ID-ul cursului nu a fost specificat.");
}

$enrollment_id = $_GET['enrollmentId'];


if (!isset($_GET['studentName'])) {
    die("student_name nu a fost specificat.");
}

$student_name = $_GET['studentName'];



if (!isset($_GET['courseName'])) {
    die("courseName nu a fost specificat.");
}

$course_name = $_GET['courseName'];

// Query pentru a obține detaliile cursului
$grade_query = "SELECT grade, grade_date , course_id FROM enrollments WHERE id = $enrollment_id";
$grade_details = db_select($grade_query)[0];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesarea formularului de actualizare
    $grade = trim($_POST['grade']);
    $date = trim($_POST['date']);

    // Validare simplă
    if (empty($grade) || empty($date)) {
        $message = "Toate câmpurile sunt obligatorii.";
    } else {
        // Verifică rolul utilizatorului pentru a ajusta query-ul

            $query = "UPDATE enrollments SET grade = ?, grade_date = ? WHERE id = $enrollment_id";
            $params = [$grade, $date];

        }
    
        // Execută query-ul
        if (db_execute($query, $params)) {
            $message = "Nota a fost actualizata cu succes.";
            header("Location: ./view-courseStudents.php?id=" . $grade_details['course_id']);
            exit;
        } else {
            $message = "A apărut o eroare. Încercați din nou.";
        }
    }
    


?>




<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adaugă/Modifică Nota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Adaugă/Modifică Nota</h4>
                    </div>
                    <div class="card-body">
                        <!-- Detalii Curs și Student -->
                        <div class="mb-4 p-3 bg-light rounded text-center">
                            <p><strong>Student:</strong> <span class="text-primary"><?php echo htmlspecialchars($student_name); ?></span></p>
                            <p><strong>Curs:</strong> <span class="text-primary"><?php echo htmlspecialchars($course_name); ?></span></p>
                        </div>

                        <!-- Afișare Mesaj -->
                        <?php if (!empty($message)): ?>
                            <div class="alert <?php echo strpos($message, 'succes') !== false ? 'alert-success' : 'alert-danger'; ?> text-center">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formularul de Notare -->
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="grade" class="form-label fw-bold">Notă</label>
                                <input type="number" id="grade" name="grade" class="form-control" 
                                       value="<?php echo htmlspecialchars($grade_details['grade'] ?? ''); ?>" 
                                       min="1" max="10" required>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label fw-bold">Data Notării</label>
                                <input type="date" id="date" name="date" class="form-control" 
                                       value="<?php echo htmlspecialchars($grade_details['grade_date'] ?? ''); ?>" 
                                       required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Salvează Nota</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>








