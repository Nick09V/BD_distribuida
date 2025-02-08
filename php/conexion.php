<?php
$usuario = 'master';
$contrasena = 'master';
$conexion_string = 'localhost/orcl'; // Ejemplo: localhost/XEPDB1

$conn = oci_connect($usuario, $contrasena, $conexion_string);

if (!$conn) {
    $error = oci_error();
    die("Conexión fallida: " . $error['message']);
}else{
    //echo "Conexión exitosa";
}
?>
