<?php
session_start();
session_destroy();

$loginpath = 'eecee_login.php'.'?en=1';
include '../../../sense_common_lib/lib/php-lib/logout.php';
?>
