<?php

require_once ('PHPMailer.php');
require_once ('Exception.php');
require_once ('SMTP.php');
require_once ('POP3.php');
//include 'db_connection.php';
//include 'curl_url_include.php';
include '../../eecee/lib/php-lib/eecee_constants.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

echo "<! DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' type='text/css' href='../../eecee/themes/reset_pass_redirect.css?'.time()'>
</head>
<body>
    <div class='container'>
        <span class='change_pass_spn'>Password updated successfully!</span><br/>
        <a href='../../eecee/eecee_login.php'><button class='btn'>Login Here</button></a>
    </div>
</body>
</html>
"
?>


