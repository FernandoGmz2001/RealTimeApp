<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

include('connection.php');
$con = conectar();


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM message";
    $query = mysqli_query($con, $sql);

    if ($query) {
        $mensajes = array();

        while ($row = mysqli_fetch_assoc($query)) {
            $mensajes[] = $row;
        }
        header('Content-Type: application/json');
        echo json_encode($mensajes);
    } else {
        // Error en la consulta
        http_response_code(500);
        echo json_encode(array('message' => 'Error en la consulta.'));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del producto enviados por el cliente
    $userId = $_POST['userId'];
    $message = $_POST['message'];

    // Validar los datos (puedes agregar más validaciones según tus necesidades)

    // Insertar el nuevo producto en la base de datos
    $sql = "INSERT INTO message (userId,message) VALUES ($userId,'$message')";
    $query = mysqli_query($con, $sql);

    if ($query) {
        // Producto agregado exitosamente
        http_response_code(201);
        echo json_encode(array('message' => 'Mensaje agregado exitosamente.'));
    } else {
        // Error al agregar el producto
        http_response_code(500);
        echo json_encode(array('message' => 'Error al agregar el mensaje.'));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Obtener los datos del producto enviados por el cliente
    $userId = $_POST['userId'];
    $message = $_POST['message'];

    // Validar los datos (puedes agregar más validaciones según tus necesidades)

    // Actualizar el mensaje en la base de datos
    $sql = "UPDATE message SET message = '$message' WHERE userId = $userId";
    $query = mysqli_query($con, $sql);

    if ($query) {
        // Mensaje actualizado exitosamente
        http_response_code(200);
        echo json_encode(array('message' => 'Mensaje actualizado exitosamente.'));
    } else {
        // Error al actualizar el mensaje
        http_response_code(500);
        echo json_encode(array('message' => 'Error al actualizar el mensaje.'));
    }
}
?>