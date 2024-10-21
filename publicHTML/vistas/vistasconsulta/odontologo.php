<?php

include("../../backend/conexion.php");
include('../../backend/extractor.php');

session_start();
reloadSession();

if(!isset($_SESSION['paciente'])) exit();

if(!reservaHabilitada($_SESSION['paciente']['idpaciente'])) exit();

$stmt = $pdo->query("SELECT idodontologo, nombre, apellido, foto FROM odontologo ORDER BY nombre ASC");

$odontologos = $stmt->fetchAll();

?>

<form method="POST" id="elegirodontologo">

    <h1 class="m-0">Seleccione un Odontólogo</h1>

    <div class="innercontainer">

        <ul>

            <?php

            foreach ($odontologos as $odontologo) {

                if(odontologoHabilitado($_SESSION['paciente']['idpaciente'], $odontologo['idodontologo'])) {
                    
                    echo "
                        <li id='" . $odontologo['idodontologo'] . "' class='odontologo'>
                            <input type='radio' id='" . $odontologo['idodontologo'] . "' name='odontologo' value='" . $odontologo['idodontologo'] . "'>
                            <label for='" . $odontologo['idodontologo'] . "'>" . $odontologo['nombre'] . " " . $odontologo['apellido'] . "</label>
                    ";

                    if(isset($odontologo['foto'])) echo "<img src='backend/almacenamiento/fotosdeperfil/{$odontologo['foto']}' alt='Foto del odontólogo'>";
                    
                    else echo "<img src='img/iconoperfil.png' alt='Foto del odontólogo'>";

                    echo "</li>";                
                }
            }

            ?>

        </ul>

        <div id="btnsmov">

            <button id='siguienteform' type="button">Siguiente</button>

        </div>

    </div>

</form>