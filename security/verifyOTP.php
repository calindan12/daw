<?php
session_start();
require_once '../db_connection.php'; // Include fișierul de conexiune la baza de date

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);

    if (empty($otp)) {
        $message = 'Introduceți codul OTP!';
    } elseif ($otp == $_SESSION['otp']) {
        // Autentificare reușită
        unset($_SESSION['otp']);
        $_SESSION['authenticated'] = true;

        // Verificăm rolul utilizatorului
        $user_id = $_SESSION['user_id']; // Presupunem că ID-ul utilizatorului este stocat în sesiune
        $stmt = $conn->prepare("SELECT idRole FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $idRole = $row['idRole'];

            if ($idRole == 2) { // Presupunem că ID-ul rolului 'student' este 2
                header("Location: ../students/student-dashboard.php");
            } else if ($idRole == 1) {
                header("Location: ../professors/professorDashboard.php");
            }else{
                header("Location: ../admin/admin-dashboard.php");
            }
        } else {
            $message = 'Eroare: Nu s-a putut determina rolul utilizatorului!';
        }

        $stmt->close();
        $conn->close();
        exit;
    } else {
        $message = 'Codul OTP este incorect!';
    }
}
?>
