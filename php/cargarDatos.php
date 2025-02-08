<?php
session_start();

include 'conexion.php';

// Activar reporte de errores (para depuración)
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Verificar si la sesión tiene la ubicación establecida
if (!isset($_SESSION['ubicacion'])) {
    echo json_encode(["error" => "Acceso no autorizado"]);
    exit();
}

// Obtener los datos de la solicitud
$data = json_decode(file_get_contents("php://input"));

// Verificar si se recibió el parámetro "tabla"
if (!$data || !isset($data->tabla)) {
    echo json_encode(["error" => "Parámetro 'tabla' no proporcionado"]);
    exit();
}

$tabla = strtolower(trim($data->tabla)); // Asegurar que no haya espacios vacíos


// Validar la ubicación de la sesión
$ubicacion = $_SESSION['ubicacion'];
$sql = "";
$sql = "SELECT * FROM Producto";
if ($ubicacion == "norte") {
    switch ($tabla) {
        case "productosnorte":
            $sql = "SELECT * FROM Producto";
            break;
        case "empleados":
            $sql = "SELECT * FROM Empleado_norte";
            break;
        case "clientes":
            $sql = "SELECT * FROM Cliente_norte";
            break;
        case "pedidos":
            $sql = "SELECT * FROM pedido_norte";
            break;
        case "sucursal":
            $sql = "SELECT * FROM sucursal";
            break;
        case "inventario":
            $sql = "SELECT * FROM Inventario";
            break;
        default:
            echo json_encode(["error" => "Tabla no permitida para la ubicación norte"]);
            exit();
            
    }
} elseif ($ubicacion == "sur") {
    switch ($tabla) {
        case "productossur":
            $sql = "SELECT * FROM producto"; // Restricción
            break;
        case "empleados":
            $sql = "SELECT * FROM Empleado_sur";
            break;
        case "clientes":
            $sql = "SELECT * FROM Cliente_sur";
            break;
        case "pedidos":
            $sql = "SELECT * FROM pedido_sur";
            break;
        case "sucursal":
            $sql = "SELECT * FROM sucursal";
            break;
        case "inventario":
            $sql = "SELECT * FROM Inventario";
            break;
        default:
            echo json_encode(["error" => "Tabla no permitida para la ubicación sur"]);
            exit();
    }
}

// Verificar si la consulta SQL es válida
if (empty($sql)) {
    echo json_encode(["error" => "Consulta inválida o tabla no permitida"]);
    exit();
}

// Verificar si la conexión a la base de datos está disponible
if (!$conn) {
    echo json_encode(["error" => "Error en la conexión a la base de datos"]);
    exit();
}

// Preparar y ejecutar la consulta SQL
$stmt = oci_parse($conn, $sql);
if (!$stmt) {
    $error = oci_error($conn);
    echo json_encode(["error" => "Error en la consulta SQL", "details" => $error['message']]);
    exit();
}

$result = oci_execute($stmt);
if (!$result) {
    $error = oci_error($stmt);
    echo json_encode(["error" => "Error al ejecutar la consulta", "details" => $error['message']]);
    exit();
}

// Obtener los resultados
$resultados = [];
while ($row = oci_fetch_assoc($stmt)) {
    $rowLowerCase = [];
    foreach ($row as $key => $value) {
        $rowLowerCase[strtolower($key)] = $value; // Convertir nombres de columna a minúsculas
    }
    $resultados[] = $rowLowerCase;
}

// Liberar recursos
oci_free_statement($stmt);
oci_close($conn);

// Devolver los resultados en formato JSON
echo json_encode(empty($resultados) ? ["message" => "No se encontraron resultados"] : $resultados);
?>