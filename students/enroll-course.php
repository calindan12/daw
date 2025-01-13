<?php
require_once '../helper/db_helper.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}


if ($_SESSION['idRole'] !== 2) {
    header("Location: access_denied.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];

    // Inserare în tabela enrollments
    $query = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
    $params = [$user_id, $course_id];

    if (db_execute($query, $params)) {
        $_SESSION['success_message'] = "Te-ai înscris cu succes la curs!";
    } else {
        $_SESSION['error_message'] = "A apărut o eroare. Încercă din nou.";
    }

    header("Location: attend-courses.php");
    exit;
}
?>
