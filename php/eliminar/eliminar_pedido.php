<?php
header('Content-Type: application/json');
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_pedido = intval($_POST['id_pedido']);

    $sql = "DELETE FROM pedido WHERE id_pedido = :id_pedido";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id_pedido", $id_pedido);

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
