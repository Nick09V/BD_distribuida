<?php
header('Content-Type: application/json');
include '../conexion.php'; // Conexión a Oracle

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_sucursal = intval($_POST['id_sucursal']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);

    $sql = "INSERT INTO sucursal (id_sucursal, direccion, telefono) 
            VALUES (:id_sucursal, :direccion, :telefono)";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id_sucursal", $id_sucursal);
    oci_bind_by_name($stmt, ":direccion", $direccion);
    oci_bind_by_name($stmt, ":telefono", $telefono);

    $result = oci_execute($stmt);

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        $error = oci_error($stmt);
        echo json_encode(["success" => false, "error" => $error['message']]);
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
?>
