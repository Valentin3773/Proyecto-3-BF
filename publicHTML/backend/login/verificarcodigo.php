<?php
session_start();
$codigo = $_SESSION['codigo'];
echo "El código almacenado en la sesión es: " . $codigo;
?>