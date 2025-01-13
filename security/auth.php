<?php
session_start();

function checkAccess() {
    // Obține ruta curentă
    $current_route = $_SERVER['REQUEST_URI'];

    // Verifică dacă ruta conține "/professors"
    if (strpos($current_route, '/professors') !== false) {
        // Verifică rolul utilizatorului
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'professor') {
            // Afișează eroare sau redirecționează
            header("Location: error.php?message=Acces interzis la rutele pentru profesori!");
            exit;
        }
    }
}
