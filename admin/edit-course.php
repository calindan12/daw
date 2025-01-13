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

if ($_SESSION['idRole'] !== 3) {
    header("Location: access_denied.php");
    exit;
}
// Include fișierul de conexiune la baza de date
require_once '../db_connection.php';


// Preia ID-ul utilizatorului din sesiune


if (isset($_GET['id'])) {
    $course_id = $_GET['id'];


    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Procesarea formularului de actualizare
        $query = "SELECT c.id, c.name, c.credits , c.id_professor ,u.id, u.nume , u.prenume FROM courses as c LEFT JOIN users u ON u.id = c.id_professor WHERE c.id = $course_id";
        $course =  db_select($query)[0];
    }


    $query_getUsers = "SELECT nume, prenume, id FROM users WHERE idRole = 1";
    $professors = db_select($query_getUsers);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesarea formularului de actualizare
    $name = trim($_POST['nume']);
    $credits = trim($_POST['credits']);
    $professor = trim($_POST['professor']);



    // Validare simplă
    if (empty($name) || empty($credits) || empty($professor)) {
        $message = "Toate câmpurile sunt obligatorii.";
    }else {

        // Actualizează informațiile utilizatorului în baza de date
        $query = "UPDATE courses SET name = ?, credits = ?, id_professor = ? WHERE id = $course_id";
        $params=[$name , $credits , $professor];

        if (db_execute($query , $params)) {
            $message = "Cursul a fost actualizat cu succes.";
            header("Location: ./manage-courses.php");
        } else {
            $message = "A apărut o eroare. Încercați din nou.";
        }
    }
}

}
?>




<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizare Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Actualizare Curs</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo strpos($message, 'succes') !== false ? 'alert-success' : 'alert-danger'; ?> text-center">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nume" class="form-label">Nume</label>
                        <input type="text" id="nume" name="nume" class="form-control" value="<?php echo htmlspecialchars($course['name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="credits" class="form-label">Credits</label>
                        <input type="number" id="credits" name="credits" class="form-control" value="<?php echo htmlspecialchars($course['credits'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="professor" class="form-label">Profesor:</label>
                        <select id="professor" name="professor" class="form-select" required>
                            <?php if (empty($course['id_professor'])): ?>
                                <!-- Afișăm opțiunea "Selectați Profesor" dacă nu există profesor asignat -->
                                <option value="" selected>-- Selectați Profesor --</option>
                            <?php endif; ?>
                            <?php foreach ($professors as $professor): ?>
                                <option value="<?php echo htmlspecialchars($professor['id']); ?>" 
                                    <?php echo ($professor['id'] == $course['id_professor']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($professor['nume'] . ' ' . $professor['prenume']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Salvează modificările</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
