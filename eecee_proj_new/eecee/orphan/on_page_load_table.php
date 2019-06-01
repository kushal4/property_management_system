<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
//echo "opening dev.php";
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
include 'lib/php-lib/common_functions.php';
include '../lib/php-lib/actl_lib.php';
include '../lib/php-lib/sec.php';
include '../lib/php-lib/session_exp.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION["user_id"];
    $prop_id = $_SESSION["prop_id"];

    $logfile->logfile_writeline("the user ID is".$user_id);
    $logfile->logfile_writeline("the prop ID is".$prop_id);

}
$logfile->logfile_close();
?>