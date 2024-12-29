<?php
session_start(); // Asigură-te că sesiunea este pornită
$message = $_SESSION['error_message'] ?? ''; // Preia mesajul de eroare
unset($_SESSION['error_message']); // Șterge mesajul după afișare
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Formular de Înregistrare</h2>
                    </div>
                    <div class="card-body">
                        <!-- Afișare mesaj de eroare -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formularul de înregistrare -->
                        <form method="POST" action="register_process1.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nume utilizator:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Parolă:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Înregistrează-te</button>
                            <div class="text-center mt-3">
                                <p>Ai deja un cont? <a href="../login/login.php" class="btn btn-link">Autentifică-te</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Bootstrap JS Bundle (Include Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
