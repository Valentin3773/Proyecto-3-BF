<script src="../lib/jquery-3.7.1.min.js"></script>
<script src="../js/utilidades.js"></script>

<?php

include('extractor.php');

echo json_encode(getHorasFinalizacionInactividad('09-10-2024', '05:00:00', '11-10-2024', 1));

?>