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

   if (!empty($data['new']) || !empty($data['newA'])) {

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
                if ($data['new'] === null || $data['new'] === '') $respuesta['error'] = "Contraseña no proporcionada";
                else if ($data['newA'] === null || $data['newA'] === '') $respuesta['error'] = "Confirmación de contraseña no proporcionada";
                else if ($data['new'] !== $data['newA']) $respuesta['error'] = "Las contraseñas no coinciden";
                else if (strlen($data['new']) > 24) $respuesta['error'] = "La contraseña es demasiado larga (debe tener entre 8 y 24 caracteres)";
                else if (strlen($data['new']) <= 7) $respuesta['error'] = "La contraseña es demasiado corta (debe tener al menos 8 caracteres)";
                else if (!preg_match('/[A-Z]/', $data['new'])) $respuesta['error'] = "La contraseña debe incluir al menos una letra mayúscula (A-Z)";
                else if (!preg_match('/[a-z]/', $data['new'])) $respuesta['error'] = "La contraseña debe incluir al menos una letra minúscula (a-z)";
                else if (!preg_match('/[0-9]/', $data['new'])) $respuesta['error'] = "La contraseña debe incluir al menos un número (0-9)";
                else if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $data['new'])) $respuesta['error'] = "La contraseña debe incluir al menos un carácter especial";   

                else {

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

                if ($data['new'] === null || $data['new'] === '') $respuesta['error'] = "Contraseña no proporcionada";
                else if ($data['newA'] === null || $data['newA'] === '') $respuesta['error'] = "Confirmación de contraseña no proporcionada";
                else if ($data['new'] !== $data['newA']) $respuesta['error'] = "Las contraseñas no coinciden";
                else if (strlen($data['new']) > 24) $respuesta['error'] = "La contraseña es demasiado larga (debe tener entre 8 y 24 caracteres)";
                else if (strlen($data['new']) <= 7) $respuesta['error'] = "La contraseña es demasiado corta (debe tener al menos 8 caracteres)";
                else if (!preg_match('/[A-Z]/', $data['new'])) $respuesta['error'] = "La contraseña debe incluir al menos una letra mayúscula (A-Z)";
                else if (!preg_match('/[a-z]/', $data['new'])) $respuesta['error'] = "La contraseña debe incluir al menos una letra minúscula (a-z)";
                else if (!preg_match('/[0-9]/', $data['new'])) $respuesta['error'] = "La contraseña debe incluir al menos un número (0-9)";
                else if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $data['new'])) $respuesta['error'] = "La contraseña debe incluir al menos un carácter especial";   

                else {

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
            } 
            catch (PDOException $e) {

                $respuesta['error'] = "Ha ocurrido un error: " . $e->getMessage();
            }
        } 
        else $respuesta['error'] = "Ha ocurrido un error";
    } else {
        
        $respuesta['enviar'] = "Una de las contraseñas esta vacia";
    }
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit();
}

?>