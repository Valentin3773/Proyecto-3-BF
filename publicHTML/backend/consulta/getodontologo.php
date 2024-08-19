<?php

include('../conexion.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') getOdontologo();

else header('Location: ../../index.php');

function getOdontologo() {

    global $pdo;

    $json = file_get_contents('php://input');

    $data = json_decode($json, true);

    if($data) {

        $ido = $data['odontologo'];

        $sql = 'SELECT nombre, apellido, foto FROM odontologo WHERE idodontologo = :ido ORDER BY nombre ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ido', $ido);

        if($stmt->execute() && $stmt->rowCount() > 0) $odontologo = $stmt->fetch(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($odontologo);
        exit();
    }
}
?>