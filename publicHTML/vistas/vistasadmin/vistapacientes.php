<?php

include("../../backend/conexion.php");
include("../../backend/extractor.php");

session_start();

if (!isset($_SESSION['odontologo'])) {

    header('Location: ../../index.php');
    exit();
}

if (!isset($_GET['idpaciente'])) {

    $ido = $_SESSION['odontologo']['idodontologo'];

    $pacientes = array();

    $consulta = "SELECT DISTINCT p.idpaciente, p.nombre, p.apellido, p.documento, p.telefono, p.foto FROM odontologo o JOIN consulta c ON o.idodontologo = c.idodontologo JOIN paciente p ON c.idpaciente = p.idpaciente WHERE o.idodontologo = :ido AND c.vigente = 'vigente' ORDER BY nombre ASC";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':ido', $ido);

    if ($stmt->execute() && $stmt->rowCount() > 0) $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

    <?php if(!empty($pacientes)) { ?>

    <div class="conpacientes">

        <?php

        foreach ($pacientes as $paciente) {

            $idp = $paciente['idpaciente'];
            $nombre = $paciente['nombre'] . " " . $paciente['apellido'];
            $documento = $paciente['documento'];
            $telefono = $paciente['telefono'];

            if (!isset($paciente['foto'])) echo "
                <div class='p-contenedor' id='{$idp}'>
                    <div class='perfil'>
                    <img src='img/iconoperfil.png' alt='Imágen de Perfil' class='foto'>
                    <div>
                        <h2 class='Nombre'>{$nombre}</h2>
                        <p class='Documento fs-5'>Documento: {$documento} </p>
                        <p class='Telefono fs-5'>Teléfono: {$documento}</p>
                    </div>
                    </div>
                </div>
            ";
            else {

                $urlfoto = "backend/almacenamiento/fotosdeperfil/{$paciente['foto']}";

                echo "
                    <div class='p-contenedor' id='{$idp}'>
                        <div class='perfil'>
                        <img src='{$urlfoto}' alt='Imágen de Perfil' class='foto'>
                        <div>
                            <h2 class='Nombre'>{$nombre}</h2>
                            <p class='Documento fs-5'>Documento: {$documento} </p>
                            <p class='Telefono fs-5'>Teléfono: {$documento}</p>
                        </div>
                        </div>
                    </div>
                ";
            }
        }

        ?>

    </div>

    <?php 
    
    }
    else echo "<div class='w-100 h-100 d-flex justify-content-center align-items-center'><h1 class='titinformativo'>Todavía no tienes ningún paciente, prueba agendando una consulta para uno</h1></div>"; 

    ?>

<?php

} 
else {

    $idp = isset($_GET['idpaciente']) ? intval(sanitizar($_GET['idpaciente'])) : null;

    $consulta = "SELECT * FROM paciente WHERE idpaciente = :idp ORDER BY nombre ASC";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    $enfermedades = array();
    $medicacion = array();

    $consulta = "SELECT enfermedad FROM enfermedades WHERE idpaciente = :idp ORDER BY enfermedad ASC";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) $enfermedades[] = $tupla;

    $consulta = "SELECT medicacion FROM medicacion WHERE idpaciente = :idp ORDER BY medicacion ASC";
    $stmt = $pdo->prepare($consulta);
    $stmt->bindParam(':idp', $idp);

    if ($stmt->execute() && $stmt->rowCount() > 0) while ($tupla = $stmt->fetch(PDO::FETCH_ASSOC)) $medicacion[] = $tupla;
?>

    <div id="pcontainer">

        <h3 class="idpaciente"><?= "ID: {$idp}" ?></h3>

        <div id="fotonom">

            <div id="fotoperfil" class="position-relative">

                <?php

                if (isset($paciente['foto'])) echo "<img src='backend/almacenamiento/fotosdeperfil/{$paciente['foto']}' alt='Foto de Perfil'>";

                else echo "<img src='img/iconoperfil.png' alt='Foto de Perfil'>";

                ?>

                <div class="lapizeditar" id="mdF" desactivado="true">
                    <img src="img/iconosvg/lapiz.svg" alt="Modificar" title="Modificar">
                </div>
                <input type="file" id="inFile" accept="image/*">

            </div>

        </div>

        <div class="datos">

            <div id="nombre" class="campo">

                <h2 class="clave">Nombre</h2>
                <input class="valor" type="text" value="<?= $paciente['nombre'] ?>" disabled></input>

            </div>

            <div id="apellido" class="campo">

                <h2 class="clave">Apellido</h2>
                <input class="valor" type="text" value="<?= $paciente['apellido'] ?>" disabled></input>

            </div>

            <div id="documento" class="campo">

                <h2 class="clave">Documento</h2>
                <input class="valor" type="text" value="<?= $paciente['documento'] ?>" disabled></input>

            </div>

            <div id="direccion" class="campo">

                <h2 class="clave">Dirección</h2>
                <input class="valor" type="text" value="<?php if (!empty($paciente['direccion'])) echo $paciente['direccion'] ?>" disabled></input>

            </div>

            <div id="telefono" class="campo">

                <h2 class="clave">Teléfono</h2>
                <input class="valor" type="text" value="<?php if (!empty($paciente['telefono'])) echo $paciente['telefono'] ?>" disabled></input>

            </div>

            <div id="email" class="campo">

                <h2 class="clave">Email</h2>
                <input class="valor" type="text" value="<?= $paciente['email'] ?>" disabled></input>

            </div>

            <div id="enfermedades">

                <h2 class="clave">Enfermedades</h2>

                <ul class="valor">

                    <?php

                    if (empty($enfermedades)) echo "<span class='enfermedad noenfermedades d-flex justify-content-center align-items-center'>No hay enfermedades</span>";

                    else foreach ($enfermedades as $enfermedad) echo "<li class='enfermedad' data-enfermedad='{$enfermedad['enfermedad']}'><span>{$enfermedad['enfermedad']}</span><div class='eliminarenfermedad invisible' data-enfermedad='{$enfermedad['enfermedad']}'><i class='fas fa-trash-alt' style='color: #ffffff;'></i></div></li>";

                    ?>

                    <div id="contagregar" class="w-100 d-flex justify-content-center align-items-center">
                        <div id="agregarenfermedad" class="invisible">
                            <div class="mas"></div>
                        </div>
                    </div>

                </ul>

            </div>

            <div id="medicacion">

                <h2 class="clave">Medicación</h2>

                <ul class="valor">

                    <?php

                    if (empty($medicacion)) echo "<span class='medicamento nomedicacion d-flex justify-content-center align-items-center'>No hay medicación</span>";

                    else foreach ($medicacion as $medicamento) echo "<li class='medicamento' data-medicamento='{$medicamento['medicacion']}'><span>{$medicamento['medicacion']}</span><div class='eliminarmedicamento invisible' data-medicamento='{$medicamento['medicacion']}'><i class='fas fa-trash-alt' style='color: #ffffff;'></i></div></li>";

                    ?>

                    <div id="contagregar" class="w-100 d-flex justify-content-center align-items-center">
                        <div id="agregarmedicacion" class="invisible">
                            <div class="mas"></div>
                        </div>
                    </div>

                </ul>

            </div>

        </div>

        <div id="botonespaciente">

            <button type="button" id="editarpaciente">Editar</button>
            <button type="button" id="guardarpaciente" disabled>Guardar</button>

        </div>

    </div>

<?php } ?>