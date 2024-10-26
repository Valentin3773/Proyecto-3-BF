<?php 
    session_start();
    include_once ('../backend/conexion.php');

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        checaruserproces();
    }
    function checaruserproces(){
       try { 
            global $pdo;
            $chekeo = "SELECT * FROM paciente WHERE email = :email";
            $stmt = $pdo->prepare($chekeo);
            $stmt->bindParam(':email', $_SESSION['user_email_address']);

            if ($stmt->execute() && $stmt->rowCount() > 0) {
                //Limpio los datos de google incluido los tokens
                include_once('config.php');
                $google_client->revokeToken();
                session_unset();

                //Carga los datos del paciente
                $tupla = $stmt->fetch(PDO::FETCH_ASSOC);
                unset($tupla['contrasenia']);
                $_SESSION['paciente'] = $tupla;

                $sql = "UPDATE paciente SET verificador = 'verificado' WHERE idpaciente = :idp";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':idp', $_SESSION['paciente']['idpaciente']);
                $stmt->execute();
                header("Location: ../");
            } 
            else {
                header("Location: logout.php");
                exit();
            }
        } catch(Exception $e) {
            echo "Error: $e";
        }
    
    }
?>