<?php
    require_once '../helper/db_helper.php';
    session_start(); 

    $message = $_SESSION['error_message'] ?? '';
    unset($_SESSION['error_message']); // Șterge mesajul după preluare


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
    
    
        $query = "SELECT email FROM users WHERE email = ?";
        $params = [$email];

    
        if(db_select($query , $params)){
            $_SESSION['error_message'] = "Există deja un utilizator cu acest email!";
            header("Location: ./register.php");
        }else{

            $role_query = "SELECT id FROM roles WHERE name = ?";
            $role_name = "student";
            $role_id = db_select($role_query , [$role_name])[0]['id'];
            if($role_id){
            }else{
                die("nu s a gasit rol de student");
            }
    
            $queryInsert = "INSERT INTO users (username, email, password, idRole) VALUES (?, ?, ?, ?)";
            $query_params = [$username , $email , $hashed_password , $role_id];

            if(db_execute($queryInsert , $query_params)){
                header("Location: ../login/login.php");
            }else{
                die("eroare la inserare");
            }

        
            if ($stmt->execute()) {
                header("Location: ../login/login.php");
            } else {
                echo "Eroare: " . $stmt->error;
            }
        
            $stmt->close();
        }
    
        // Obținem ID-ul rolului de student
    
    }



$conn->close();
?>
