<?php
function conectar()
{
    $servidor = 'localhost';
    $basededatos = "neuraldeskmessages";
    $usuario = "root";
    $password = "";
    // $port = "3308";

    $conexion = mysqli_connect($servidor, $usuario, $password, $basededatos) or die("No se conecto");

    mysqli_select_db($conexion, $basededatos);

    return $conexion;
}
?>