<?php
session_start();

// Verifica si la solicitud es POST y tiene contenido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(file_get_contents("php://input"))) {
    $data = json_decode(file_get_contents("php://input"));

    // Validar que el JSON tenga la clave "ubicacion"
    if (isset($data->ubicacion)) {
        $ubicacionSolicitada = strtolower(trim($data->ubicacion)); // Normaliza la ubicación
        $ipUsuario = $_SERVER['REMOTE_ADDR']; // Obtiene la IP del usuario

        // Simulación de IPs permitidas para cada ubicación
        $ipsPermitidas = [
            "norte" => ["::1", "192.168.100.15"],
            "sur" => ["::1", "192.168.2.101"],
        ];

        // Verifica si la ubicación solicitada existe y si la IP está permitida
        if (array_key_exists($ubicacionSolicitada, $ipsPermitidas) && in_array($ipUsuario, $ipsPermitidas[$ubicacionSolicitada])) {
            $_SESSION['ubicacion'] = $ubicacionSolicitada;
            $accesoPermitido = true;
            $mensaje = "Acceso permitido para la ubicación: " . $ubicacionSolicitada;
        } else {
            $accesoPermitido = false;
            $mensaje = "Acceso denegado. IP no permitida para la ubicación: " . $ubicacionSolicitada;
        }

        // Respuesta JSON con la IP del usuario y el resultado del acceso
        echo json_encode([
            "success" => $accesoPermitido,
            "ipUsuario" => $ipUsuario,
            "mensaje" => $mensaje
        ]);
    } else {
        // Si falta la clave "ubicacion" en el JSON
        echo json_encode([
            "success" => false,
            "ipUsuario" => $_SERVER['REMOTE_ADDR'],
            "mensaje" => "Error: La clave 'ubicacion' es requerida"
        ]);
    }
} else {
    // Si la solicitud no es POST o no tiene contenido
    echo json_encode([
        "success" => false,
        "ipUsuario" => $ubicacionSolicitada,
        "mensaje" => "Error: Solicitud no válida"
    ]);
}
?>