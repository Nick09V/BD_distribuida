<?php
header('Content-Type: application/json');
include '../conexion.php'; // Conexión a Oracle

    $id_cliente = intval($_POST['id_cliente']);
    $id_sucursal = intval($_POST['id_sucursal']);
    $nombre_cli = trim($_POST['nombre_cli']);
    $apellido_cli = trim($_POST['apellido_cli']);
    $cedula = trim($_POST['cedula']);
    $correo = trim($_POST['correo']);

    $sql = "INSERT INTO cliente_sur (id_cliente, id_sucursal, nombre_cli, apellido_cli, cedula, correo) 
            VALUES ($id_cliente, $id_sucursal, '$nombre_cli', '$apellido_cli', '$cedula', '$correo')";

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
