<?php

include('config.php');

$google_client->revokeToken();

session_destroy();

header("Location: login.php?estado=6");

?>