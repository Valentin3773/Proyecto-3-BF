<?php

$host = "localhost";
$db = "clinica";
$charset = 'utf8mb4';
$user = "root";
$pass = "£9Z151XE.FrDl_$[V_e+3[?S>";

$options = [

    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {

    $pdo = new PDO($dsn, $user, $pass, $options);
} 
catch (PDOException $e) {

    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

?>