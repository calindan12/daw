$server = 'kcpgm0ka8vudfq76.chr7pe7iynqr.eu-west-1.rds.amazonaws.com';
$username = 'faar5mqcqyixyakm';
$password = 'rmogf75j27wz9ssf';
$database = 'ubq5am3b39gc5oal';
$port = 3306;

$conn = new mysqli($server, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Conexiunea a eșuat: " . $conn->connect_error);
}
echo "Conexiune reușită!";
