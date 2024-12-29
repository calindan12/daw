<?php
    require_once '../db_connection.php';

    function db_select($query , $params = []){
        global $conn;
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Eroare la pregătirea query-ului: " . $conn->error);
        }
        if(!empty($params)){
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }


    function db_execute($query , $params = []){
        global $conn;
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Eroare la pregătirea query-ului: " . $conn);
        }

        if (!empty($params)) {
            $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        }

        $success = $stmt->execute();

        if (!$success) {
            die("Eroare la execuția query-ului: " . $stmt->error);
        }
    
        $stmt->close();
        return $success;


    }


