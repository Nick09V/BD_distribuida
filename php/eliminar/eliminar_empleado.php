<?php
header('Content-Type: application/json');
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_empleado = intval($_POST['id_empleado']);

    $sql = "DELETE FROM empleado WHERE id_empleado = :id_empleado";
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
