<?php
require_once '../helper/db_helper.php';
session_start(); 

// Mesajele de eroare și succes
$message = $_SESSION['error_message'] ?? '';
$success_message = $_SESSION['success_message'] ?? '';

// Șterge mesajele după ce sunt afișate
unset($_SESSION['error_message'], $_SESSION['success_message']); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validare simplă
    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Toate câmpurile sunt obligatorii!";
        header("Location: ./add-professor.php");
        exit;
    }

    // Hash parola
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verificare dacă email-ul există deja
    $query = "SELECT email FROM users WHERE email = ?";
    $params = [$email];

    if (db_select($query, $params)) {
        $_SESSION['error_message'] = "Există deja un profesor cu acest email!";
        header("Location: ./add-professor.php");
        exit;
    } else {
        // Obține ID-ul rolului de profesor
        $role_query = "SELECT id FROM roles WHERE name = ?";
        $role_name = "professor";
        $role_result = db_select($role_query, [$role_name]);

        if ($role_result) {
            $role_id = $role_result[0]['id'];
        } else {
            die("Nu s-a găsit rolul de profesor.");
        }

        // Inserare în baza de date
        $queryInsert = "INSERT INTO users (username, email, password, idRole) VALUES (?, ?, ?, ?)";
        $query_params = [$username, $email, $hashed_password, $role_id];

        if (db_execute($queryInsert, $query_params)) {
            $_SESSION['success_message'] = "Profesorul a fost înregistrat cu succes!";
            header("Location: ./add-professor.php");
            exit;
        } else {
            $_SESSION['error_message'] = "A apărut o eroare la inserare.";
            header("Location: ./add-professor.php");
            exit;
        }
    }
}
?>



<?php
require_once '../analytics/analytics.php';
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Professor</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Adăugare Profesor</h2>
                    </div>
                    <div class="card-body">
                        <!-- Mesaj de eroare -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Mesaj de succes -->
                        <?php if (!empty($success_message)): ?>
                            <div class="alert alert-success">
                                <?php echo htmlspecialchars($success_message); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formularul de înregistrare -->
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nume utilizator:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Parolă:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Adaugă Profesor</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle (Include Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
