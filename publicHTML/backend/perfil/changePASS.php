<?php 

include("../conexion.php");
include("../extractor.php");

session_start();

if(!isset($_SESSION['paciente']) && !isset($_SESSION['odontologo'])) header('Location: index.php');

if($_SERVER['REQUEST_METHOD'] == "POST") changePassword();

function changePassword() {

    global $pdo;

    $respuesta = array();
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if(!$data) exit();

    $data['new'] = sanitizar($data['new']);
    $data['newA'] = sanitizar($data['newA']);
    $data['old'] = sanitizar($data['old']);

    if($data['new'] == $data['newA']) {

        if (isset($_SESSION['odontologo'])) {

            $ido = $_SESSION['odontologo']['idodontologo'];

            try {

                $contraseniaOld = $data['old'];

                $consulta = "SELECT contrasenia FROM odontologo WHERE idodontologo = :ido";
                $stmt = $pdo->prepare($consulta);
                $stmt->bindParam(':ido', $ido);
                $stmt->execute();
                $tupla = $stmt->fetch(PDO::FETCH_ASSOC);

                $hashedPassword = $tupla['contrasenia'];

                if(password_verify($contraseniaOld, $hashedPassword)) {

                    try {

                        $pass = $data['new'];
                        $pass = password_hash($pass, PASSWORD_BCRYPT);
                        $consulta = "UPDATE odontologo SET contrasenia = :pass WHERE idodontologo = :ido";
                        $stmt = $pdo->prepare($consulta);
                        $stmt->bindParam(':pass', $pass);
                        $stmt->bindParam(':ido', $ido);
                        $stmt->execute();
                        $respuesta['enviar'] = "Contraseña Actualizada";
                        reloadSession();
                    }
                    catch(PDOException $e) {

                        $respuesta['enviar'] = $e;
                    }
                } 
                else $respuesta['error'] = "La contraseña actual no coincide con el usuario";
            } 
            catch (PDOException $e) {

                $respuesta['error'] = "Ha ocurrido un error: " . $e->getMessage();
            }
        } 
        else if (isset($_SESSION['paciente'])) {

            $idp = $_SESSION['paciente']['idpaciente'];

            try {

                $contraseniaOld = $data['old'];

                $consulta = "SELECT contrasenia FROM paciente WHERE idpaciente = :idp";
                $stmt = $pdo->prepare($consulta);
                $stmt->bindParam(':idp', $idp);
                $stmt->execute();
                $tupla = $stmt->fetch(PDO::FETCH_ASSOC);

                $hashedPassword = $tupla['contrasenia'];

                if(password_verify($contraseniaOld, $hashedPassword)) {

                    try {

                        $pass = $data['new'];
                        $pass = password_hash($pass, PASSWORD_BCRYPT);
                        $consulta = "UPDATE paciente SET contrasenia = :pass WHERE idpaciente = :idp";
                        $stmt = $pdo->prepare($consulta);
                        $stmt->bindParam(':pass', $pass);
                        $stmt->bindParam(':idp', $idp);
                        $stmt->execute();
                        $respuesta['enviar'] = "Contraseña Actualizada";
                        reloadSession();
                    }
                    catch(PDOException $e) {

                        $respuesta['enviar'] = $e;
                    }
                } 
                else $respuesta['error'] = "La contraseña actual no coincide con el usuario";
            } 
            catch (PDOException $e) {

                $respuesta['error'] = "Ha ocurrido un error: " . $e->getMessage();
            }
        } 
        else $respuesta['error'] = "Ha ocurrido un error";

    } 
    else $respuesta['error'] = "Las contraseñas repetidas no son iguales";
    
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>