<?php
header('Content-Type: application/json');
include '../conexion.php';

$ubicacion = trim($_POST['varUbicacion']);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_empleado = intval($_POST['id_empleado']);

    if ($ubicacion == "norte") {
        $sql = "DELETE FROM empleado_norte WHERE id_empleado = :id_empleado";
    }else{
        $sql = "DELETE FROM empleado_sur WHERE id_empleado = :id_empleado";
    }
    
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id_empleado", $id_empleado);

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
