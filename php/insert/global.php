<?php
session_start();
$ubicacion = $_SESSION['ubicacion'];
if ($ubicacion == 'norte') {
    echo "norte";
}



?>