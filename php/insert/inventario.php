<?php
header('Content-Type: application/json');
include '../conexion.php'; // Conexión a Oracle

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_inventario = intval($_POST['id_inventario']);
    $id_producto = intval($_POST['id_producto']);
    $id_sucursal = intval($_POST['id_sucursal']);
    $cantidad = intval($_POST['cantidad']);

    $sql = "INSERT INTO inventario (id_inventario, id_producto, id_sucursal, cantidad) 
            VALUES (:id_inventario, :id_producto, :id_sucursal, :cantidad)";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id_inventario", $id_inventario);
    oci_bind_by_name($stmt, ":id_producto", $id_producto);
    oci_bind_by_name($stmt, ":id_sucursal", $id_sucursal);
    oci_bind_by_name($stmt, ":cantidad", $cantidad);

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
