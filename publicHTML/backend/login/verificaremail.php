<?php 

include("../conexion.php");

if($_SERVER['REQUEST_METHOD'] == 'GET') verifyUserEmail();

else {

    header('Location: ../../index.php');
    exit();
}

function verifyUserEmail() : void {

    global $pdo;

    $idp = $_GET['idp'] ?? null;

    $verificador = $_GET['verificador'] ?? null;

    if($idp == null || $verificador == null) {

        header('Location: ../../index.php');
        return;
        echo 1;
    }

    $sql = "SELECT idpaciente, verificador FROM paciente WHERE idpaciente = :idp";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idp', $idp);

    if($stmt->execute() && $stmt->rowCount() == 1) {

        $tupla = $stmt->fetch();

        if($tupla['verificador'] == 'verificado') {
            
            header('Location: ../../login.php?estado=4');
            return;
        }

        if(hash_equals($tupla['verificador'], hash_hmac('sha256', $verificador, 'tremendaclinica2024'))) {

            $sql = "UPDATE paciente SET verificador = 'verificado' WHERE idpaciente = :idp";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idp', $idp);

            if($stmt->execute()) header('Location: ../../login.php?estado=5');

            else header('Location: ../../login.php?estado=4');
        }
        else header('Location: ../../login.php?estado=4');
    }
    else header('Location: ../../index.php');
}

?>