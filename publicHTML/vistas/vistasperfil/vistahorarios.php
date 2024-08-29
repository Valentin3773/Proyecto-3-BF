<?php

session_start();

include("../../backend/extractor.php");

reloadSession();

if(isset($_SESSION['odontologo'])):

?>

<h1 class="subtitulo">Mis Horarios</h1>

<?php endif; ?>