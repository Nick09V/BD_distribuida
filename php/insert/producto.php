<?php

include '../conexion.php';


    $id_producto = $_POST['id_producto'];
    $nombre_producto = $_POST['nombre_producto'];
    $precio = $_POST['precio'];
    $precio = floatval($precio);

    $sql = "INSERT INTO Producto (id_producto, nombre_producto, precio) 
    VALUES ($id_producto, '$nombre_producto', $precio)";

$stid = oci_parse($conn, $sql);


// Ejecutar la consulta
if (oci_execute($stid, OCI_COMMIT_ON_SUCCESS)) {
    echo json_encode(array("status" => "success"));
} else {
    $e = oci_error($stid);
    echo json_encode(array("status" => "error", "message" => $e['message']));
}

oci_free_statement($stid);
oci_close($conn);
?>
