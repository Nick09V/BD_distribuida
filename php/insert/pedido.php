<?php
header('Content-Type: application/json');
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_pedido = intval($_POST['id_pedido']);
    $id_sucursal = intval($_POST['id_sucursal']);
    $id_cliente = intval($_POST['id_cliente']);
    $id_empleado = intval($_POST['id_empleado']);
    $id_producto = intval($_POST['id_producto']);
    $num_pedido = intval($_POST['num_pedido']);
    $fecha_pedido = $_POST['fecha_pedido'];
    $cantidad = floatval($_POST['cantidad']);

    $ubicacion = trim($_POST['varUbicacion']);
    

    if ($ubicacion == "norte") {
        $sql = "INSERT INTO pedido_norte (id_pedido, id_sucursal, id_cliente, id_empleado, id_producto, num_pedido, fecha_pedido, cantidad) 
            VALUES (:id_pedido, :id_sucursal, :id_cliente, :id_empleado, :id_producto, :num_pedido, TO_DATE(:fecha_pedido, 'YYYY-MM-DD'), :cantidad)";
    }else{
        $sql = "INSERT INTO pedido_sur (id_pedido, id_sucursal, id_cliente, id_empleado, id_producto, num_pedido, fecha_pedido, cantidad) 
            VALUES (:id_pedido, :id_sucursal, :id_cliente, :id_empleado, :id_producto, :num_pedido, TO_DATE(:fecha_pedido, 'YYYY-MM-DD'), :cantidad)";
    }

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":id_pedido", $id_pedido);
    oci_bind_by_name($stmt, ":id_sucursal", $id_sucursal);
    oci_bind_by_name($stmt, ":id_cliente", $id_cliente);
    oci_bind_by_name($stmt, ":id_empleado", $id_empleado);
    oci_bind_by_name($stmt, ":id_producto", $id_producto);
    oci_bind_by_name($stmt, ":num_pedido", $num_pedido);
    oci_bind_by_name($stmt, ":fecha_pedido", $fecha_pedido);
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
