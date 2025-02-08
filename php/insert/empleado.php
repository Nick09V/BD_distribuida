<?php
header('Content-Type: application/json');
include '../conexion.php';
$ubicacion = $_SESSION['ubicacion'];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_empleado = intval($_POST['id_empleado']);
    $id_sucursal = intval($_POST['id_sucursal']);
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);
    $telefono = trim($_POST['telefono']);

    $sql = "INSERT INTO empleado_norte (id_empleado, id_sucursal, nombre, direccion, telefono) 
            VALUES (:id_empleado, :id_sucursal, :nombre, :direccion, :telefono)";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id_empleado", $id_empleado);
    oci_bind_by_name($stmt, ":id_sucursal", $id_sucursal);
    oci_bind_by_name($stmt, ":nombre", $nombre);
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
