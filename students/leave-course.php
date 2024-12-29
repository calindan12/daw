<?php
require_once '../helper/db_helper.php';
session_start();

// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Verifică dacă este o cerere POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];

    // Query pentru ștergerea înscrierii
    $query = "DELETE FROM enrollments WHERE user_id = ? AND course_id = ?";
    $params = [$user_id, $course_id];

    if (db_execute($query, $params)) {
        $_SESSION['success_message'] = "Ați părăsit cursul cu succes.";
    } else {
        $_SESSION['error_message'] = "A apărut o eroare. Încercați din nou.";
    }

    header("Location: student-courses.php");
    exit;
}
?>
