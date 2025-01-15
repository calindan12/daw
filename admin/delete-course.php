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

// Verifică dacă ID-ul este transmis prin GET
if (isset($_GET['id'])) {
    $courseId = $_GET['id']; // Preia ID-ul cursului

    // Verifică dacă ID-ul este valid (numeric)
    if (!is_numeric($courseId)) {
        die("ID-ul cursului este invalid.");
    }

    global $conn; // Utilizează conexiunea definită în `db_helper.php`

    try {
        // Dezactivează autocommit pentru a începe tranzacția
        $conn->autocommit(false);

        // Șterge înregistrările din tabela enrollments asociate cu acest curs
        $deleteEnrollmentsQuery = "DELETE FROM enrollments WHERE course_id = ?";
        db_execute($deleteEnrollmentsQuery, [$courseId]);

        // Șterge cursul din tabela courses
        $deleteCourseQuery = "DELETE FROM courses WHERE id = ?";
        db_execute($deleteCourseQuery, [$courseId]);

        // Confirmă tranzacția
        $conn->commit();
        $_SESSION['success_message'] = "Cursul și înregistrările asociate au fost șterse cu succes.";
    } catch (Exception $e) {
        // Anulează tranzacția în caz de eroare
        $conn->rollback();
        $_SESSION['error_message'] = "Eroare la ștergerea cursului sau a înregistrărilor asociate: " . $e->getMessage();
    } finally {
        // Reactivează autocommit
        $conn->autocommit(true);
    }

    // Redirecționează înapoi la pagina Manage Courses
    header("Location: ./manage-courses.php");
    exit;
} else {
    die("ID-ul cursului nu a fost specificat.");
}
?>
