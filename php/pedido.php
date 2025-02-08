<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['empleado_id'])) {
    echo json_encode(["message" => "No autenticado"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

$numPedido = $data->numPedido;
$fechaPedido = $data->fechaPedido;
$cantidad = $data->cantidad;
$productoId = $data->productoId;
$empleadoId = $_SESSION['empleado_id'];
$clienteId = 1; // Suponiendo un cliente fijo.

$direccion = $_SESSION['direccion'];

if ($direccion == "norte") {
    $sql = "INSERT INTO Pedido (num_pedido, fecha_pedido, cantidad, id_producto, id_empleado, id_cliente)
            VALUES (:numPedido, TO_DATE(:fechaPedido, 'YYYY-MM-DD'), :cantidad, :productoId, :empleadoId, :clienteId)";
} else {
    $sql = "INSERT INTO Pedido (num_pedido, fecha_pedido, cantidad, id_producto, id_empleado, id_cliente)
            VALUES (:numPedido, TO_DATE(:fechaPedido, 'YYYY-MM-DD'), :cantidad, :productoId, :empleadoId, :clienteId)";
}

$stmt = oci_parse($conn, $sql);

oci_bind_by_name($stmt, ":numPedido", $numPedido);
oci_bind_by_name($stmt, ":fechaPedido", $fechaPedido);
oci_bind_by_name($stmt, ":cantidad", $cantidad);
oci_bind_by_name($stmt, ":productoId", $productoId);
oci_bind_by_name($stmt, ":empleadoId", $empleadoId);
oci_bind_by_name($stmt, ":clienteId", $clienteId);

$result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

if ($result) {
    echo json_encode(["message" => "Pedido registrado correctamente"]);
} else {
    $error = oci_error($stmt);
    echo json_encode(["message" => "Error al registrar el pedido: " . $error['message']]);
}

oci_free_statement($stmt);
oci_close($conn);
?>
