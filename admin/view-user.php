<?php

require_once '../helper/db_helper.php';


session_start();

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include fișierul de conexiune la baza de date
require_once '../db_connection.php';

// Preia ID-ul utilizatorului din sesiune
$user_id = $_SESSION['user_id'];

if(isset($_GET['id'])){
    $userId = $_GET['id'];
    if (!is_numeric($userId)) {
        die("ID-ul utilizatorului este invalid.");
    }
    $query = "SELECT username, email,nume, prenume , nume_facultate FROM users WHERE id = ?";
    $params = $userId;


    $user = db_select($query , [$params])[0];
    if (!$user) {
        die("Utilizatorul nu a fost găsit sau există o problemă cu baza de date.");
    }

    $conn->close();
}

// Query pentru a prelua informațiile utilizatorului

?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4 text-center"><?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'Nume indisponibil'; ?></h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <p><strong>User Name:</strong> <?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'username indisponibil'; ?></p>
                        <p><strong>Email:</strong> <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'Email indisponibil'; ?></p>
                        <p><strong>Nume:</strong> <?php echo isset($user['nume']) ? htmlspecialchars($user['nume']) : 'Nume indisponibil'; ?></p>
                        <p><strong>Prenume:</strong> <?php echo isset($user['prenume']) ? htmlspecialchars($user['prenume']) : 'prenume indisponibil'; ?></p>
                        <p><strong>Facultate:</strong> <?php echo isset($user['nume_facultate']) ? htmlspecialchars($user['nume_facultate']) : 'nume_facultate indisponibil'; ?></p>
                    </div>
                    <div class="card-footer text-center">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
