<?php

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

$data = json_decode(file_get_contents('php://input'), true);

$respuesta = [];

if($data) {

    $idp = $data['idpaciente'];
    $enfermedad = $data['enfermedad'];

    $sql = "DELETE FROM enfermedades WHERE idpaciente = :idp AND enfermedad = :enfermedad";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);
    $stmt->bindParam(':enfermedad', $enfermedad);

    if($stmt->execute()) $respuesta['exito'] = "Se ha eliminado la enfermedad";

    else $respuesta['error'] = "Ha ocurrido un error al eliminar la enfermedad";
}
else $respuesta['error'] = "Ha ocurrido un error al eliminar la enfermedad";

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>