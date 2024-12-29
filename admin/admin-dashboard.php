<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirecționare dacă utilizatorul nu este autentificat
    header("Location: login_form.php");
    exit;
}

// Inițializează cURL
// $url = "https://unibuc.ro/studii/facultati/";
// $ch = curl_init();

// // Setează opțiunile cURL
// curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// // Obține conținutul paginii
// $html = curl_exec($ch);

// // Închide conexiunea cURL
// curl_close($ch);

// // Verifică dacă conținutul a fost preluat
// if ($html === false) {
//     die("Eroare la preluarea paginii.");
// }

// // Încarcă HTML-ul în DOMDocument pentru parsare
// $dom = new DOMDocument();
// libxml_use_internal_errors(true); // Ignoră erorile de validare HTML
// $dom->loadHTML($html);
// libxml_clear_errors();

// // Găsește elementele care conțin numele facultăților
// $xpath = new DOMXPath($dom);
// $facultyNodes = $xpath->query("//h2[@class='no-underline']");

// // Afișează numele facultăților
// $faculties = [];
// foreach ($facultyNodes as $node) {
//     $faculties[] = trim($node->nodeValue);
// }
?>


<?php
require_once '../analytics/analytics.php';
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../navbar/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">Bine ai venit admin, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="../logout/logout.php" class="btn btn-danger mt-3">Deconectează-te</a>

        <!-- <div class="mt-5">
            <h2 class="text-center mb-4">Facultăți disponibile</h2>
            <div class="row">
                <?php foreach ($faculties as $faculty): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($faculty); ?></h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
