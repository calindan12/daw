<?php


require_once '../helper/db_helper.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// Verifică dacă utilizatorul este autentificat
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Include fișierul de conexiune la baza de date
require_once '../db_connection.php';


// Preia ID-ul utilizatorului din sesiune
$user_id = $_SESSION['user_id'];
$message = '';






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



$query = "SELECT nume, prenume , nume_facultate, idRole , email , username FROM users WHERE id = $user_id";

$user = db_select($query)[0];
if ($user) {
} else {
    die("Utilizatorul nu a fost găsit sau există o problemă cu baza de date.");
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesarea formularului de actualizare
    $nume = trim($_POST['name']);
    $prenume = trim($_POST['firstName']);
    $nume_facultate = ($user['idRole'] == 2) ? trim($_POST['faculty']) : '';
    $email = trim($_POST['email']);
    // Validare simplă
    if (empty($nume) || empty($prenume)) {
        $message = "Toate câmpurile sunt obligatorii.";
    } else {
        // Verifică rolul utilizatorului pentru a ajusta query-ul
        if ($user['idRole'] == 2) { // Student
            $query = "UPDATE users SET nume = ?, prenume = ?, nume_facultate = ? WHERE id = $user_id";
            $params = [$nume, $prenume, $nume_facultate];
        } else if ($user['idRole'] == 1) { // Admin sau alte roluri
            $query = "UPDATE users SET nume = ?, prenume = ? WHERE id = $user_id";
            $params = [$nume, $prenume];
        }else{
            $username = trim($_POST['username']);
            $query = "UPDATE users SET nume = ?, prenume = ?, username = ? , email = ? WHERE id = $user_id";
            $params = [$nume, $prenume , $username , $email];

        }
    
        // Execută query-ul
        if (db_execute($query, $params)) {
            $message = "Userul a fost actualizat cu succes.";
            header("Location: ./viewProfile.php");
            exit;
        } else {
            $message = "A apărut o eroare. Încercați din nou.";
        }
    }
    
}

// Preia informațiile actuale ale utilizatorului pentru afișare

$conn->close();
?>





<?php
require_once '../analytics/analytics.php';
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
                        <label for="name" class="form-label">Nume</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['nume'] ?? ''); ?>" required>
                        </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">Prenume</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo htmlspecialchars($user['prenume']??''); ?>" required>
                    </div>
                    <div class="mb-3">
                    <?php if ($idRole == 2): ?>
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
                    </div>

                    <div class="mb-3">
                    <?php if ($idRole == 3): ?>
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>

                         <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    <?php endif; ?>
                    </div>


                    <button type="submit" class="btn btn-success w-100">Salvează modificările</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
