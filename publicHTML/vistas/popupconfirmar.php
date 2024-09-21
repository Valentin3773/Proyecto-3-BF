<?php

$cont = $_GET['Contenido'];
$aviso = $_GET['Aviso'];
$txtcancelar = $_GET['txtcancelar'];
$txtconfirmar = $_GET['txtconfirmar'];

?>

<div class = "cont-mensaje-Popup d-flex">
    <div class = "mensaje-Popup">
        <div class ="popup-contTitulo"><h1 id="popup-Titulo"><?= $aviso ?></h1></div>
        <div class ="popup-Contenido"><h2 id="popup-Cont" ><?= $cont ?></h2></div>
        <div class ="popup-contBtn">
            <button class="btnCancelar" id="btnCancelar"><h2><?= $txtcancelar ?></h2></button>
            <button class="btnConfirmar" id="btnConfirmar"><h2><?= $txtconfirmar ?></h2>
        </div>
    </div>
</div>