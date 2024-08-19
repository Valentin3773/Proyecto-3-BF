<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = array();
    $response ['error'] = "Funca";
}
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>