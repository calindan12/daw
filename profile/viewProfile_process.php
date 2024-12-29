<?php
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

// Query pentru a prelua informațiile utilizatorului
$query = "SELECT name, email, enrollment_date, idRole FROM students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifică dacă utilizatorul există
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    die("Utilizatorul nu a fost găsit.");
}

$stmt->close();
$conn->close();
?>
