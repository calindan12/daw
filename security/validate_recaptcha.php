<?php
require '../vendor/autoload.php';

use Google\Cloud\RecaptchaEnterprise\V1\RecaptchaEnterpriseServiceClient;
use Google\Cloud\RecaptchaEnterprise\V1\Event;

function validateReCAPTCHA($recaptchaKey, $token, $projectId, $action) {
    $client = new RecaptchaEnterpriseServiceClient();
    $projectName = $client->projectName($projectId);

    $event = (new Event())
        ->setSiteKey($recaptchaKey)
        ->setToken($token);

    $assessment = (new \Google\Cloud\RecaptchaEnterprise\V1\Assessment())
        ->setEvent($event);

    try {
        $response = $client->createAssessment($projectName, $assessment);

        if (!$response->getTokenProperties()->getValid()) {
            echo 'Token invalid: ' . $response->getTokenProperties()->getInvalidReason() . PHP_EOL;
            return false;
        }

        if ($response->getTokenProperties()->getAction() !== $action) {
            echo 'Acțiunea din reCAPTCHA nu corespunde.' . PHP_EOL;
            return false;
        }

        $score = $response->getRiskAnalysis()->getScore();
        echo 'Scorul reCAPTCHA este: ' . $score . PHP_EOL;

        // Interpretează scorul reCAPTCHA (de exemplu: >= 0.5 este utilizator valid)
        return $score >= 0.5;

    } catch (Exception $e) {
        echo 'Eroare reCAPTCHA: ' . $e->getMessage() . PHP_EOL;
        return false;
    }
}

// Preia datele din formular
$recaptchaKey = '6LdJvaMqAAAAAN81oFmZA0-HA3BevxrlT-OWRJSy'; // reCAPTCHA site key
$token = $_POST['recaptcha-token']; // Tokenul generat pe client
$projectId = 'proiect-1734964462786'; // Google Cloud Project ID
$action = 'LOGIN'; // Acțiunea definită în reCAPTCHA

if (validateReCAPTCHA($recaptchaKey, $token, $projectId, $action)) {
    echo "reCAPTCHA valid. Procesăm autentificarea...";
    // Continuă procesarea autentificării
} else {
    echo "Eroare: reCAPTCHA invalid.";
}
?>
