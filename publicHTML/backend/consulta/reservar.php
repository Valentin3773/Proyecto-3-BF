<?php

include('../conexion.php');

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if($data) {

    
}

?>