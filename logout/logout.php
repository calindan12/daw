<?php
session_start();
session_destroy(); // Distruge sesiunea
header("Location: ../login/login.php"); // Redirecționează către pagina de login
exit;
?>
