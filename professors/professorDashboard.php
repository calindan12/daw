<?php
require_once '../security/auth.php';
session_start();

// Verifică autentificarea
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit;
}

// Verifică rolul utilizatorului (exemplu pentru admini)
if ($_SESSION['idRole'] !== 1) {
    header("Location: access_denied.php");
    exit;
}

require_once '../analytics/analytics.php';
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Profesor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <h1>Bine ai venit, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Rolul tău este: <strong><?php echo htmlspecialchars($_SESSION['user_role']); ?></strong></p>
        <a href="../logout/logout.php" class="btn btn-danger">Deconectează-te</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
