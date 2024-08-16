<?php
    $cont = $_GET['Contenido'];
    $aviso = $_GET['Aviso'];
?>
<link rel="stylesheet" href="css/popupmensaje.css">
<div class = "cont-mensaje-Popup d-flex">
    <div class = "mensaje-Popup">
        <div class ="popup-contTitulo"><h1 id="popup-Titulo"><?= $aviso ?></h1></div>
        <div class ="popup-Contenido"><h2 id="popup-Cont" ><?= $cont ?></h2></div>
        <div class ="popup-contBtn"><button class="btnCerrar" id="btnCerrar"><h2>Cerrar</h2></button></div>
    </div>
</div>    


