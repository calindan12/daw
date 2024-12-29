<?php
// Detalii conexiune
$servername = "localhost";
$username = "root"; // Schimbă cu utilizatorul MySQL
$password = ""; // Schimbă cu parola MySQL
$dbname = "proiect";

// Creare conexiune
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificare conexiune
if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}
?>
