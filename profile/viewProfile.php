<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Include fișierul de conexiune la baza de date
require_once '../db_connection.php';

// Preia ID-ul utilizatorului din sesiune
$user_id = $_SESSION['user_id'];

// Query pentru a prelua informațiile utilizatorului
$query = "SELECT username, email,nume, prenume , nume_facultate, idRole FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user = [];
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    die("Utilizatorul nu a fost găsit sau există o problemă cu baza de date.");
}

$stmt->close();
$conn->close();
?>




<?php
require_once '../analytics/analytics.php';
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilul meu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Profilul meu</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h5 class="card-title">Informații despre utilizator</h5>
                    </div>
                    <div class="card-body text-center">
                        <p><strong>User Name:</strong> <?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'username indisponibil'; ?></p>
                        <p><strong>Email:</strong> <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'Email indisponibil'; ?></p>
                        <p><strong>Nume:</strong> <?php echo isset($user['nume']) ? htmlspecialchars($user['nume']) : 'Nume indisponibil'; ?></p>
                        <p><strong>Prenume:</strong> <?php echo isset($user['prenume']) ? htmlspecialchars($user['prenume']) : 'prenume indisponibil'; ?></p>
                        <?php if (isset($user['idRole']) && $user['idRole'] == 2):?>
                            <p><strong>Facultate:</strong> <?php echo isset($user['nume_facultate']) ? htmlspecialchars($user['nume_facultate']) : 'nume_facultate indisponibil'; ?></p>
                        <?php endif; ?>
                        <p><strong>Rol:</strong> 
                            <?php 
                            if (isset($user['idRole'])) {
                                echo $user['idRole'] == 1 ? 'Professor' : ($user['idRole'] == 2 ? 'Student' : 'Admin');
                            } else {
                                echo 'Rol indisponibil';
                            }
                            ?>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="updateProfile.php" class="btn btn-primary btn-lg w-50">
                            <i class="bi bi-pencil-square"></i> Update
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
