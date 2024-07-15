<?php

include("../../backend/conexion.php");

session_start();

if(!isset($_SESSION['odontologo'])) header('Location: ../../index.php');

echo "Servicios"
?>