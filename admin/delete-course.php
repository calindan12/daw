<?php
require_once '../helper/db_helper.php';
session_start();

// Verifică dacă utilizatorul este autentificat și este admin
if (!isset($_SESSION['user_id']) || $_SESSION['idRole'] != 3) { // Verifică dacă utilizatorul este admin
    header("Location: ../login/login.php");
    exit;
}

// Verifică dacă ID-ul este transmis prin GET
if (isset($_GET['id'])) {
    $courseId = $_GET['id']; // Preia ID-ul utilizatorului

    // Verifică dacă ID-ul este valid (numeric)
    if (!is_numeric($courseId)) {
        die("ID-ul cursului este invalid.");
    }

    // Query pentru ștergerea utilizatorului din baza de date
    $deleteQuery = "DELETE FROM courses WHERE id = ?";
    $params = [$courseId];

    if (db_execute($deleteQuery, $params)) {
        $_SESSION['success_message'] = "Cursul a fost șters cu succes.";
    } else {
        $_SESSION['error_message'] = "Eroare la ștergerea Cursului.";
    }

    // Redirecționează înapoi la pagina Manage Users
    header("Location: ./manage-courses.php");
    exit;
} else {
    die("ID-ul cursului nu a fost specificat.");
}
?>
