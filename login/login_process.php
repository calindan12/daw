<?php
require_once '../db_connection.php';
require_once 'D:/xampp2/htdocs/proiect/vendor/autoload.php';
require_once '../helper/db_helper.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    // $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    // Validare reCAPTCHA
    $secretKey = '6LcBC6QqAAAAAFXqbxTQnP8uGlhICN1sLCGwIVF8'; // Înlocuiește cu cheia secretă reCAPTCHA
    $recaptchaUrl = "https://www.google.com/recaptcha/api/siteverify";
    $response = file_get_contents("$recaptchaUrl?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);



    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    if (empty($recaptchaResponse)) {
        echo "<script>console.error('Tokenul reCAPTCHA lipsește.');</script>";
        $_SESSION['login_message'] = "Tokenul reCAPTCHA lipsește. Încercați din nou.";
        header("Location: login.php");
        exit;
    }

    if (!$responseKeys['success']) {
        // Log răspunsul în consola browserului
        echo "<script>console.error('Eroare reCAPTCHA: " . json_encode($responseKeys) . "');</script>";
    
        // Setează mesajul de eroare pentru utilizator
        $_SESSION['login_message'] = "Eroare reCAPTCHA. Te rugăm să încerci din nou.";
        header("Location: login.php");
        exit;
    }

    if (empty($email) || empty($password)) {
        $_SESSION['login_message'] = "Toate câmpurile sunt obligatorii!";
        header("Location: login.php");
        exit;
    }

    // Verificarea utilizatorului în baza de date
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    if (!$stmt) {
        $_SESSION['login_message'] = "Eroare internă. Încercați mai târziu.";
        header("Location: login.php");
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Generarea OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['idRole'] = $user['idRole'];


            // Trimiterea OTP prin email folosind PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'sandbox.smtp.mailtrap.io';
                $mail->SMTPAuth = true;
                $mail->Port = 2525;
                $mail->Username = 'f6c238a108900f';
                $mail->Password = 'aa3e6d6a8e7ee9';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                $mail->setFrom('no-reply@aplicatia-ta.com', 'Aplicația Ta');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Codul OTP';
                $mail->Body = '<h1>Codul dvs. OTP este: ' . $otp . '</h1>';
                $mail->AltBody = 'Codul dvs. OTP este: ' . $otp;

                $mail->send();
                header("Location: ../security/verify_otp_form.php");
                exit;
            } catch (Exception $e) {
                $_SESSION['login_message'] = "Email nu a putut fi trimis. Eroare: {$mail->ErrorInfo}";
                header("Location: login.php");
                exit;
            }
        } else {
            $_SESSION['login_message'] = "Parola este incorectă!";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['login_message'] = "Nu există un cont asociat cu acest email!";
        header("Location: login.php");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>
