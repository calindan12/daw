<?php
require_once '../helper/db_helper.php';
session_start();

// Verifică dacă utilizatorul este autentificat și este admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

if ($_SESSION['idRole'] !== 3) {
    header("Location: access_denied.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name']);
    $credits = trim($_POST['credits']);
    $professor_id = $_POST['professor'] ?? '';

    // Validare simplă
    if (empty($course_name) || empty($credits) || empty($professor_id)) {
        $message = "Toate câmpurile sunt obligatorii!";
    } elseif (!is_numeric($credits)) {
        $message = "Numărul de credite trebuie să fie numeric!";
    } else {
        // Query pentru inserare în baza de date
        $query = "INSERT INTO courses (name, credits, id_professor) VALUES (?, ?, ?)";
        $params = [$course_name, $credits, $professor_id];

        if (db_execute($query, $params)) {
            $message = "Cursul a fost adăugat cu succes!";
        } else {
            $message = "A apărut o eroare la adăugarea cursului.";
        }
    }
}

// Preia lista profesorilor
$query_getUsers = "SELECT nume, prenume, id FROM users WHERE idRole = 1";
$professors = db_select($query_getUsers);

?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adaugă Curs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Adaugă Curs</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo strpos($message, 'succes') !== false ? 'alert-success' : 'alert-danger'; ?> text-center">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="course_name" class="form-label">Nume Curs</label>
                        <input type="text" id="course_name" name="course_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="credits" class="form-label">Număr de Credite</label>
                        <input type="number" id="credits" name="credits" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="professor" class="form-label">Profesor:</label>
                        <select id="professor" name="professor" class="form-select" required>
                            <option value="" disabled selected>-- Selectați Profesorul --</option>
                            <?php foreach ($professors as $professor): ?>
                                <option value="<?php echo htmlspecialchars($professor['id']); ?>">
                                    <?php echo htmlspecialchars($professor['nume'] . ' ' . $professor['prenume']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Adaugă Curs</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
