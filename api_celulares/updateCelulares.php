<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['error' => 'Solo se permite el método PUT']);
    exit();
}

require 'conexionSakila.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input["id"], $input["Marca"], $input["Modelo"], $input["Precio"], $input["Almacenamiento"], $input["Fecha_lanzamiento"])) {
    http_response_code(400);
    echo json_encode(["error" => "Datos incompletos"]);
    exit();
}

$id = intval($input["id"]);
$Marca = $conn->real_escape_string($input["Marca"]);
$Modelo = $conn->real_escape_string($input["Modelo"]);
$Precio = floatval($input["Precio"]);
$Almacenamiento = intval($input["Almacenamiento"]);
$Fecha_lanzamiento = $conn->real_escape_string($input["Fecha_lanzamiento"]);

$query = "UPDATE celulares SET Marca = ?, Modelo = ?, Precio = ?, Almacenamiento = ?, Fecha_lanzamiento = ? WHERE id = ?";

$st = $conn->prepare($query);

if (!$st) {
    http_response_code(500);
    echo json_encode(["error" => "Error en la consulta", "detalle" => $conn->error]);
    exit();
}

$st->bind_param("ssdisi", $Marca, $Modelo, $Precio, $Almacenamiento, $Fecha_lanzamiento, $id);

if ($st->execute()) {
    if ($st->affected_rows > 0) {
        echo json_encode(["message" => "Celular actualizado correctamente"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "No se encontró el celular con id: $id"]);
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al ejecutar", "detalle" => $st->error]);
}

$st->close();
$conn->close();
?>
