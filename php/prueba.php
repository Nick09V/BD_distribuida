<?php
include 'conexion.php';

// Leer el JSON enviado en la solicitud
$data = json_decode(file_get_contents("php://input"), true);

// Verificar si 'tabla' existe en la solicitud
if (!isset($data['tabla'])) {
    echo json_encode(["error" => "No se recibió la tabla"]);
    exit();
}

$tabla = $data['tabla'];

if ($tabla == "productosNorte") {
    //$sql = "SELECT ID_producto as producto, precio as precio FROM Producto";
    $sql = "SELECT * FROM Producto";
} elseif ($tabla == "empleados") {
    $sql = "SELECT * FROM Empleado_norte";
} elseif ($tabla == "clientes") {
    $sql = "SELECT * FROM Cliente_norte";
} elseif ($tabla == "pedidos") {
    $sql = "SELECT * FROM pedido_norte";
} else {
    echo json_encode(["error" => "Tabla no permitida para la ubicacion norte"]);
    exit();
}

//$sql = "SELECT COUNT(*) as conteo FROM Producto";
$stid = oci_parse($conn, $sql);
oci_execute($stid);

$result = array();
while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    $result[] = $row;
}
echo json_encode($result);

oci_free_statement($stid);
oci_close($conn);
?>