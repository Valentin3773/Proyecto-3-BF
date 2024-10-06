<script src="../lib/jquery-3.7.1.min.js"></script>
<script src="../js/utilidades.js"></script>

<?php

include('extractor.php');

echo json_encode(getFechaHorasConsulta('2024-10-15', '18:00:00', 120));

?>