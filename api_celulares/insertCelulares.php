<?php
    header("contect-Type: application/json");

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(405);
        echo json_encode(['error' => 'Solo metodo POST es permitido']);
    }

    //conectar a la BD sakila
    require 'conexionSakila.php';

    $data = json_decode(file_get_contents('php://input'), true);
    $Marca = $data['Marca'];
    $Modelo = $data['Modelo'];
    $Precio = $data['Precio'];
    $Almacenamiento = $data['Almacenamiento'];
    $Fecha_lanzamiento = $data['Fecha_lanzamiento'];

    $query = $conn->prepare("INSERT INTO celulares (Marca, Modelo, Precio, Almacenamiento, Fecha_lanzamiento) VALUES (?,?,?,?,?)");

    if(!$query){
        http_response_code(500);
        echo json_encode(["error" => "Ocurrio un error"]);
        exit;
    }

    $query->bind_param("ssiis", $Marca, $Modelo, $Precio, $Almacenamiento, $Fecha_lanzamiento);

    if($query->execute()){
        echo json_encode(["mensaje" => "celular insertado correctamente", "celular_id" => $query->insert_id]);
    }else{
        http_response_code(500);
        echo json_encode(["error" => "Fallor a la insercion", $query->error]);
    }

    $query->close();
    $conn->close();

?>
