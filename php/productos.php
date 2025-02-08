<?php
session_start();
include 'conexion.php';

$sql = "SELECT id_producto, nombreproducto, precio FROM Producto";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);

$productos = [];

while ($row = oci_fetch_assoc($stmt)) {
    $productos[] = $row;
}

oci_free_statement($stmt);
oci_close($conn);

header('Content-Type: application/json');
echo json_encode($productos);
?>
