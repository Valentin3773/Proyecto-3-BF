<?php

session_start();

include("../../backend/extractor.php");

reloadSession();

if(isset($_SESSION['odontologo'])):

?>

<h1 class="subtitulo">Mis Inactividades</h1>

<?php endif; ?>