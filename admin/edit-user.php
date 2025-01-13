<?php


require_once '../helper/db_helper.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

if ($_SESSION['idRole'] !== 3) {
    header("Location: access_denied.php");
    exit;
}

// Include fișierul de conexiune la baza de date
require_once '../db_connection.php';


// Preia ID-ul utilizatorului din sesiune


if (isset($_GET['id'])) {
    $userId = $_GET['id']; // Preia ID-ul utilizatorului


    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Procesarea formularului de actualizare
        $query = "SELECT nume, prenume , nume_facultate,email,username , idRole FROM users WHERE id = $userId";
        $user =  db_select($query)[0];
    }


    // Inițializează cURL
$url = "https://upb.ro/facultati/";
$ch = curl_init();

// Setează opțiunile cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Obține conținutul paginii
$html = curl_exec($ch);

// Închide conexiunea cURL
curl_close($ch);

// Verifică dacă conținutul a fost preluat
if ($html === false) {
    die("Eroare la preluarea paginii.");
}

// Încarcă HTML-ul în DOMDocument pentru parsare
$dom = new DOMDocument();
libxml_use_internal_errors(true); // Ignoră erorile de validare HTML
$dom->loadHTML($html);
libxml_clear_errors();

// Găsește elementele care conțin numele facultăților
$xpath = new DOMXPath($dom);
$facultyNodes = $xpath->query("//div[contains(@class, 'vc_custom_heading vc_gitem-post-data vc_gitem-post-data-source-post_title')]//h3");

// Afișează numele facultăților
$faculties = [];
foreach ($facultyNodes as $node) {
    $faculties[] = trim($node->nodeValue);
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesarea formularului de actualizare
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $nume = trim($_POST['name']);
    $prenume = trim($_POST['firstName']);
    $nume_facultate = trim($_POST['faculty']);


    // Validare simplă
    if (empty($nume) || empty($prenume) || empty($username) ||empty($email)) {
        $message = "Toate câmpurile sunt obligatorii.";
    }else {


        // Actualizează informațiile utilizatorului în baza de date
        $query = "UPDATE users SET nume = ?, prenume = ?, nume_facultate = ? , email = ? , username = ? WHERE id = $userId";
        $params=[$nume , $prenume , $nume_facultate , $email ,  $username];

        if (db_execute($query , $params)) {
            $message = "Profilul a fost actualizat cu succes.";
            header("Location: ./manage-users.php");
        } else {
            $message = "A apărut o eroare. Încercați din nou.";
        }
    }
}


}


?>







<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizare Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Actualizare Profil</h1>

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo strpos($message, 'succes') !== false ? 'alert-success' : 'alert-danger'; ?> text-center">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nume</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['nume'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">Prenume</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo htmlspecialchars($user['prenume']??''); ?>" required>
                    </div>
                    
                    <?php if ($user['idRole'] == 2): ?>
                        <label for="faculty" class="form-label">Facultate:</label>
                        <select id="faculty" name="faculty" class="form-select" required>

                            <option value="<?php echo htmlspecialchars($user['nume_facultate'] ?? ''); ?>">
                                <?php echo htmlspecialchars($user['nume_facultate'] ?? '-- Selectați Facultatea --'); ?>
                            </option>


                                <?php foreach ($faculties as $faculty): ?>
                            <option value="<?php echo htmlspecialchars($faculty); ?>">
                                <?php echo htmlspecialchars($faculty); ?>
                            </option>
                                <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-success w-100">Salvează modificările</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
