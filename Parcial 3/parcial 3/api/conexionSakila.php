<?php


    $host = '192.168.1.21';
    $user = 'sakila_user';
    $pass = 'sakila';
    $dbname = 'sakila';


    //crear conexión
    $conn = new mysqli($host, $user, $pass, $dbname);


    //verificar conexión
    if($conn->connect_error){
        http_response_code(500);
        echo json_encode(['error' => 'Error de conexión: ' . $conn->connect_error]);
        exit;
    }

?>