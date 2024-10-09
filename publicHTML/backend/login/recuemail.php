<?php 
include ('../conexion.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    recuperaremail();
} else {
    exit();
}
function recuperaremail(){
    $nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $apellido = htmlspecialchars(strip_tags($_POST['apellido']));
    $documento= htmlspecialchars(strip_tags($_POST['documento']));

    if(!is_string($nombre) || $nombre == null || $nombre == ""){
        echo "Porfavor verifique que ingreso los datos correctamente ej: nombre";
    } else if(!is_string($apellido) || $apellido == null || $apellido == ""){
        echo "Porfavor verifique que ingreso los datos correctamente ej: apellido";

    //ctype_digit porque is_int no anda porque el formulario es string cuando se envia
    } else if(!ctype_digit($documento) ||$documento == null || $documento == ""){
        echo "Porfavor verifique que ingreso los datos correctamente ej: documento".$documento;
    } else {
        global $pdo;
        $chekeo = "SELECT email FROM paciente WHERE nombre = :nombre AND apellido = :apellido AND documento = :documento";
        $stmt = $pdo->prepare($chekeo);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':documento', $documento);
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $tupla = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Se comprobo su indentidad, esta es su email: ".$tupla['email'];
            exit();
        } else {
            echo "No se pudo encontrar un usuario con los datos ingresados";
            exit();
        }
    }
}
?>