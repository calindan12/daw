<?php
require_once '../db_connection.php';
require_once 'D:/xampp2/htdocs/proiect/vendor/autoload.php';
require_once '../helper/db_helper.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = $_SESSION['login_message'] ?? '';
unset($_SESSION['login_message']); // Curăță mesajul după afișare

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // Validare reCAPTCHA
    $secretKey = '6Ld-Q6QqAAAAAEMy6KmZjeU5w4pJEXeX0xv5N_Qo'; // Înlocuiește cu cheia secretă reCAPTCHA
    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify";
    $response = file_get_contents("$recaptchaUrl?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    // Debug în consola browserului
    echo "<script>console.log('Token reCAPTCHA primit: $recaptchaResponse');</script>";
    echo "<script>console.log('Răspuns reCAPTCHA: " . json_encode($responseKeys) . "');</script>";

    if (!$responseKeys['success']) {
        echo "<script>console.error('Eroare reCAPTCHA: " . json_encode($responseKeys['error-codes']) . "');</script>";
        $message = "Eroare reCAPTCHA. Te rugăm să încerci din nou.";
    } elseif (empty($email) || empty($password)) {
        $message = "Toate câmpurile sunt obligatorii!";
    } else {
        // Verificarea utilizatorului în baza de date
        $check_query =  "SELECT id, username, password , idRole FROM users WHERE email = ?";

        
        
        $check_params = [$email];
        $result = db_select($check_query , $check_params);
        if(!$result){
            $_SESSION['error_message'] = "Nu exista cont cu acest email!";
            header("Location: login.php");
            exit;
        }else{
            if (sizeof($result) === 1) {
                $user = $result[0];

                if (password_verify($password, $user['password'])) {
                    // Generarea OTP
                    $otp = rand(100000, 999999);
                    $_SESSION['otp'] = $otp;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['idRole'] = $user['idRole'];



                    echo "<script>console.log('Login reușit. OTP generat: $otp');</script>";

                    // Trimiterea OTP prin email folosind PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; // Server SMTP Gmail
                        $mail->SMTPAuth = true;
                        $mail->Username = 'calindanmarinescu@gmail.com'; // Înlocuiți cu adresa dvs. Gmail
                        $mail->Password = 'qtmr atqu lfrb qpcu'; // Înlocuiți cu parola de aplicație Gmail
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Utilizați STARTTLS
                        $mail->Port = 587; // Portul SMTP pentru Gmail

                    
                        $mail->setFrom('calindanmarinescu@gmail.com', 'DAW'); // Adresa de e-mail de trimitere
                        $mail->addAddress($email); // Adresa destinatarului
                    
                        $mail->isHTML(true);
                        $mail->Subject = 'Codul OTP';
                        $mail->Body = '<h1>Codul dvs. OTP este: ' . $otp . '</h1>';
                        $mail->AltBody = 'Codul dvs. OTP este: ' . $otp;
                    
                        echo "<script>console.log('am ajuns aici');</script>";
                        $mail->send();
                        echo "<script>console.log('Email trimis cu succes.');</script>";
                        header("Location: ../security/verify_otp_form.php");
                        exit;
                    } catch (Exception $e) {
                        echo "<script>console.error('Eroare la trimiterea emailului: {$mail->ErrorInfo}');</script>";
                        $message = "Email nu a putut fi trimis. Încercați mai târziu.";
                    }
                    
                    }
                } else {
                    $message = "Parola este incorectă!";
                }
            } 
        }        
    }
    $conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Autentificare</h2>
                    </div>
                    <div class="card-body">
                        <!-- Afișare mesaje de eroare -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($message); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formularul de login -->
                        <form id="login-form" method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Parolă:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="g-recaptcha" data-sitekey="6Ld-Q6QqAAAAAOF5zIa0mfEWsLNLgqv5-8VAhpWA"></div>
                            <button type="submit" class="btn btn-primary w-100">Autentifică-te</button>
                            <div class="text-center mt-3">
                                <p>Nu ai cont? <a href="../register/register.php" class="btn btn-link">Înregistrează-te</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
