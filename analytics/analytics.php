<?php
// Conexiune la baza de date
require_once '../db_connection.php';
require_once '../helper/db_helper.php';


// Colectează datele utilizatorului
$user_ip = $_SERVER['REMOTE_ADDR']; // Adresa IP
$user_agent = $_SERVER['HTTP_USER_AGENT']; // Browserul și sistemul de operare
$page_url = $_SERVER['REQUEST_URI']; // URL-ul paginii vizitate
$visit_time = date('Y-m-d H:i:s'); // Timpul vizitei

// Salvează datele în baza de date
$query = "INSERT INTO analytics (ip_address, user_agent, page_url, visit_time) VALUES (?, ?, ?, ?)";
$params = [$user_ip, $user_agent, $page_url, $visit_time];

if (db_execute($query, $params)) {
    // echo "Vizita a fost înregistrată.";
} else {
    echo "Eroare la înregistrarea vizitei.";
}
?>

