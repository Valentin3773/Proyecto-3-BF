<?php

include('../conexion.php');
include('../extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['odontologo'])) exit();

function agregarEnfermedad(string $enfermedad, int $idp): bool {

    global $pdo;

    $sql = "INSERT INTO enfermedades (idpaciente, enfermedad) VALUES (:idp, :enfermedad)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);
    $stmt->bindParam(':enfermedad', $enfermedad);

    if($stmt->execute()) return true;

    else return false;
}

function agregarMedicacion(string $medicacion, int $idp): bool {

    global $pdo;

    $sql = "INSERT INTO medicacion (idpaciente, medicacion) VALUES (:idp, :medicacion)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);
    $stmt->bindParam(':medicacion', $medicacion);

    if($stmt->execute()) return true;

    else return false;
}

function obtenerViejosDatos(int $idp): array {

    global $pdo;

    $sql = "SELECT * FROM paciente WHERE idpaciente = :idp";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);
    if($stmt->execute() && $stmt->rowCount() == 1) $paciente = $stmt->fetchAll();

    $enfermedades = [];
    $medicacion = [];

    $sql = "SELECT enfermedad FROM enfermedades WHERE idpaciente = :idp";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);
    if($stmt->execute() && $stmt->rowCount() > 0) $enfermedades = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $sql = "SELECT medicacion FROM medicacion WHERE idpaciente = :idp";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);
    if($stmt->execute() && $stmt->rowCount() > 0) $medicacion = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $paciente[0]['enfermedades'] = $enfermedades;
    $paciente[0]['medicacion'] = $medicacion;

    return $paciente[0];
}

$data = json_decode(file_get_contents('php://input'), true);

/*
$data = [

    'idpaciente' => 14,
    'documento' => '56672944',
    'direccion' => 'juansonlanson',
    'email' => 'pablonpablero@gmail.com',
    'enfermedades' => ['Andresismo', 'Felipinitis crónica'],
    'medicacion' => ['Perifar', 'Paracetamol']
];
*/

$respuesta = [];

if($data) {

    $nuevosdatos = $data;

    $idp = intval(sanitizar($nuevosdatos['idpaciente']));

    $viejosdatos = obtenerViejosDatos($idp);

    $tuduben = true;

    if(sanitizarArray($nuevosdatos['enfermedades']) != $viejosdatos['enfermedades']) {

        $sql = "DELETE FROM enfermedades WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idp', $idp);
        $stmt->execute();

        foreach($nuevosdatos['enfermedades'] as $enfermedad) if(!agregarEnfermedad(sanitizar($enfermedad), $idp)) $tuduben = false;
    }

    if((isset($nuevosdatos['medicacion']) && isset($viejosdatos['medicacion']) && sanitizarArray($nuevosdatos['medicacion']) != $viejosdatos['medicacion']) || (isset($nuevosdatos['medicacion']) && !isset($viejosdatos['medicacion']))) {

        $sql = "DELETE FROM medicacion WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idp', $idp);
        
        if(!$stmt->execute()) $tuduben = false;

        foreach($nuevosdatos['medicacion'] as $medicamento) if(!agregarMedicacion(sanitizar($medicamento), $idp)) $tuduben = false;
    }

    if((isset($nuevosdatos['nombre']) && isset($viejosdatos['nombre']) && sanitizarArray($nuevosdatos['nombre']) != $viejosdatos['nombre']) || (isset($nuevosdatos['nombre']) && !isset($viejosdatos['nombre']))) {

        $nuevosdatos['nombre'] = sanitizar($nuevosdatos['nombre']);

        $sql = "UPDATE paciente SET nombre = :nombre WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idp', $idp);
        $stmt->bindParam(':nombre', $nuevosdatos['nombre']);
        
        if(!$stmt->execute()) $tuduben = false;
    }

    if((isset($nuevosdatos['apellido']) && isset($viejosdatos['apellido']) && $nuevosdatos['apellido'] != $viejosdatos['apellido']) || (isset($nuevosdatos['apellido']) && !isset($viejosdatos['apellido']))) {

        $nuevosdatos['apellido'] = sanitizar($nuevosdatos['apellido']);

        $sql = "UPDATE paciente SET apellido = :apellido WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idp', $idp);
        $stmt->bindParam(':apellido', $nuevosdatos['apellido']);
        
        if(!$stmt->execute()) $tuduben = false;
    }

    if((isset($nuevosdatos['documento']) && isset($viejosdatos['documento']) && $nuevosdatos['documento'] != $viejosdatos['documento']) || (isset($nuevosdatos['documento']) && !isset($viejosdatos['documento']))) {

        $nuevosdatos['documento'] = sanitizar($nuevosdatos['documento']);

        $sql = "UPDATE paciente SET documento = :documento WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idp', $idp);
        $stmt->bindParam(':documento', $nuevosdatos['documento']);
        
        if(!$stmt->execute()) $tuduben = false;
    }

    if((isset($nuevosdatos['telefono']) && isset($viejosdatos['telefono']) && $nuevosdatos['telefono'] != $viejosdatos['telefono']) || (isset($nuevosdatos['telefono']) && !isset($viejosdatos['telefono']))) {

        $nuevosdatos['telefono'] = sanitizar($nuevosdatos['telefono']);

        $sql = "UPDATE paciente SET telefono = :telefono WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idp', $idp);
        $stmt->bindParam(':telefono', $nuevosdatos['telefono']);
        
        if(!$stmt->execute()) $tuduben = false;
    }

    if((isset($nuevosdatos['direccion']) && isset($viejosdatos['direccion']) && $nuevosdatos['direccion'] != $viejosdatos['direccion']) || (isset($nuevosdatos['direccion']) && !isset($viejosdatos['direccion']))) {

        $nuevosdatos['direccion'] = sanitizar($nuevosdatos['direccion']);

        $sql = "UPDATE paciente SET direccion = :direccion WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idp', $idp);
        $stmt->bindParam(':direccion', $nuevosdatos['direccion']);
        
        if(!$stmt->execute()) $tuduben = false;
    }

    if((isset($nuevosdatos['email']) && isset($viejosdatos['email']) && $nuevosdatos['email'] != $viejosdatos['email']) || (isset($nuevosdatos['email']) && !isset($viejosdatos['email']))) {

        $nuevosdatos['email'] = sanitizar($nuevosdatos['email']);

        $sql = "UPDATE paciente SET email = :email WHERE idpaciente = :idp";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idp', $idp);
        $stmt->bindParam(':email', $nuevosdatos['email']);
        
        if(!$stmt->execute()) $tuduben = false;
    }

    if($tuduben) $respuesta['exito'] = "Los datos del paciente han sido modificados";

    else $respuesta['error'] = "Ha ocurrido un error al modificar los datos del paciente";
}
else $respuesta['error'] = "Ha ocurrido un error al modificar los datos del paciente";

header('Content-Type: application/json');
echo json_encode($respuesta);
exit();

?>