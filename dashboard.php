<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirecționare dacă utilizatorul nu este autentificat
    header("Location: login_form.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Bine ai venit, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <a href="./logout/logout.php">Deconectează-te</a>
</body>
</html>
