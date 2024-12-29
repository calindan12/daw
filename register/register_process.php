<?php
// Include fișierul de conexiune
require_once '../db_connection.php';


session_start(); // Asigură-te că sesiunea este pornită



$message = $_SESSION['error_message'] ?? '';
unset($_SESSION['error_message']); // Șterge mesajul după preluare


// Procesarea datelor din formular
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validare simplă
    if (empty($username) || empty($email) || empty($password)) {
        die("Toate câmpurile sunt obligatorii!");
    }



    // Hash parola
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $role_query1 = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $user_email = $email;
    $role_query1->bind_param("s", $user_email);
    $role_query1->execute();
    $role_result1 = $role_query1->get_result();

    if($role_result1->num_rows != 0){
        $_SESSION['error_message'] = "Există deja un utilizator cu acest email!";
        echo("salut");
        echo($_SESSION['error_message']);
        $role_query1->close();
        header("Location: ./register.php");
    }else{

        $role_query = $conn->prepare("SELECT id FROM roles WHERE name = ?");
        $role_name = 'student';
        $role_query->bind_param("s", $role_name);
        $role_query->execute();
        $role_result = $role_query->get_result();
    
        if ($role_result->num_rows === 1) {
            $role_row = $role_result->fetch_assoc();
            $role_id = $role_row['id'];
        } else {
            die("Rolul de student nu există în baza de date!");
        }
        $role_query->close();
        
        // Inserare în baza de date cu idRole
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, idRole) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $username, $email, $hashed_password, $role_id);
    
        if ($stmt->execute()) {
            header("Location: ../login/login.php");
        } else {
            echo "Eroare: " . $stmt->error;
        }
    
        $stmt->close();
    }

    // Obținem ID-ul rolului de student

}

// Închidere conexiune
$conn->close();
?>
