<?php
session_start();
include 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['ubicacion'])) {
    echo json_encode(["error" => "Acceso no autorizado"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));
$tabla = strtolower($data->tabla);
$valores = $data->valores;

$sql = "";

// Lógica para inserción basada en ubicación
if ($_SESSION['ubicacion'] === "norte") {
    switch ($tabla) {
        case "cliente":
            $sql = "INSERT INTO Cliente_norte (id_cliente, id_sucursal, nombre_cli, apellido_cli, cedula, correo)
                    VALUES (:id_cliente, :id_sucursal, :nombre_cli, :apellido_cli, :cedula, :correo)";
            break;
        case "empleado":
            $sql = "INSERT INTO Empleado_norte (id_empleado, id_sucursal, nombre, direccion, telefono)
                    VALUES (:id_empleado, :id_sucursal, :nombre, :direccion, :telefono)";
            break;
        case "pedido":
            $sql = "INSERT INTO Pedido_norte (id_pedido, id_sucursal, id_cliente, id_empleado, id_producto, num_pedido, fecha_pedido, cantidad)
                    VALUES (:id_pedido, :id_sucursal, :id_cliente, :id_empleado, :id_producto, :num_pedido, TO_DATE(:fecha_pedido, 'YYYY-MM-DD'), :cantidad)";
            break;
        case "producto":
            $sql = "INSERT INTO Producto (id_producto, nombre_producto, precio)
                    VALUES (:id_producto, :nombre_producto, :precio)";
            break;
        case "inventario":
            $sql = "INSERT INTO Inventario (id_inventario, id_producto, id_sucursal, cantidad)
                    VALUES (:id_inventario, :id_producto, :id_sucursal, :cantidad)";
            break;
        case "sucursal":
            $sql = "INSERT INTO Sucursal (id_sucursal, direccion, telefono)
                    VALUES (:id_sucursal, :direccion, :telefono)";
            break;
    }
} elseif ($_SESSION['ubicacion'] === "sur") {
    switch ($tabla) {
        case "cliente":
            $sql = "INSERT INTO Cliente (id_cliente, id_sucursal, nombre_cli, apellido_cli, cedula, correo)
                    VALUES (:id_cliente, :id_sucursal, :nombre_cli, :apellido_cli, :cedula, :correo)";
            break;
        case "empleado":
            $sql = "INSERT INTO Empleado (id_empleado, id_sucursal, nombre, direccion, telefono)
                    VALUES (:id_empleado, :id_sucursal, :nombre, :direccion, :telefono)";
            break;
        case "pedido":
            $sql = "INSERT INTO Pedido (id_pedido, id_sucursal, id_cliente, id_empleado, id_producto, num_pedido, fecha_pedido, cantidad)
                    VALUES (:id_pedido, :id_sucursal, :id_cliente, :id_empleado, :id_producto, :num_pedido, TO_DATE(:fecha_pedido, 'YYYY-MM-DD'), :cantidad)";
            break;
        default:
            echo json_encode(["error" => "No tienes permiso para insertar en esta tabla"]);
            exit();
    }
}

// Si la consulta SQL está vacía, la tabla no es válida
if (empty($sql)) {
    echo json_encode(["error" => "Tabla no válida"]);
    exit();
}

// Preparar la consulta
$stmt = oci_parse($conn, $sql);
if (!$stmt) {
    $error = oci_error($conn);
    echo json_encode(["error" => "Error en la consulta SQL", "details" => $error['message']]);
    exit();
}

// Bind de parámetros
foreach ($valores as $key => $value) {
    oci_bind_by_name($stmt, ":" . $key, $valores->$key);
}

// Ejecutar la consulta
$result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
if (!$result) {
    $error = oci_error($stmt);
    echo json_encode(["error" => "Error al ejecutar la consulta", "details" => $error['message']]);
} else {
    echo json_encode(["success" => "Datos insertados correctamente"]);
}

oci_free_statement($stmt);
oci_close($conn);
?>
