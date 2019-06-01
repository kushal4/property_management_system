
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
include 'lib/php-lib/common_functions.php';
include '../lib/php-lib/sec.php';
include '../lib/php-lib/actl_lib.php';
//include 'lib/php-lib/eecee_sec_map.php';
//include 'curl_url_include.php';

include '../lib/php-lib/session_exp.php';


$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

/*
$projectKey = "24MPKCZOM4DGZYU0SRU4MJ8VIXNK5X6F1LJPFT2RH0WHZA0G3W3OX9MT60GWYPSZ78ONPFU8YCXVX06I7GQJ4SR29ZSONG31I7J1VM1YB4ZUL16F47YR9J4PQVOZWFVR";
$hierarchy_true = "true";

//$data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id_decoded, "sig_arr"=>$role_sig_arr));
$data=array("param"=>array("key"=>$projectKey, "client_id"=>43, "sig_arr"=>["FRONTENDSUPERADMIN"], "hierarchy"=>$hierarchy_true));

$data_str = json_encode($data,JSON_UNESCAPED_SLASHES);

//Fetch role tree
$actlRetObj_orig = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL

$actlRetObj = '{"d":"{\"status\":0,\"message\":null}"}';
echo "echo actlRetObj<br>";
echo $actlRetObj;
echo"<br><br>";
echo "var_dump actlRetObj<br>";
var_dump($actlRetObj);

echo "<br><br>";
$decodedJson = json_decode($actlRetObj, true);
echo ("var_dump decodedJson<br>");
var_dump($decodedJson);
echo "<br><br>";

$jsonDataSTR = $decodedJson['d']."\n";
//$logfile->logfile_writeline("The jsonDataSTR :: ".$jsonDataSTR);
$data_json_decode = json_decode($jsonDataSTR, true);
var_dump($data_json_decode);
echo "<br><br>";


$test_var_node = new stdClass();
$test_var_node->status = 0;
$test_var_node->message = null;
$test_var_node_str = json_encode($test_var_node);

$test_var = new stdClass();

$test_var->d = $test_var_node_str;

echo ("var_dump test_var<br>");
var_dump($test_var);
echo "<br><br>";
//$test_var_str = json_encode($test_var);

$test_var_str = '{"d":"{\"status\":0,\"message\":null}"}';
echo "echo test_var_str<br>";
echo $test_var_str;
echo"<br><br>";
echo "var_dump test_var_str<br>";
var_dump($test_var_str);

echo "<br><br>";
$test_var_str_json_decoded = json_decode($test_var_str);
echo ("var_dump test_var_str_json_decoded<br>");
var_dump($test_var_str_json_decoded);

*/



$actlRetObj = '{"d":"{\"status\":0,\"message\":null}"}';
echo "echo actlRetObj<br>";
echo $actlRetObj;
echo"<br><br>";
echo "var_dump actlRetObj<br>";
var_dump($actlRetObj);

echo "<br><br>";
$decodedJson = json_decode($actlRetObj, true);
echo ("var_dump decodedJson<br>");
var_dump($decodedJson);
echo "<br><br>";

/*********************************************** */

$test_var_str2 = '{"d":"{\"status\":0,\"message\":null}"}';
echo "echo test_var_str2<br>";
echo $test_var_str2;
echo"<br><br>";
echo "var_dump test_var_str2<br>";
var_dump($test_var_str2);

echo "<br><br>";
$test_var_str2_json_decoded = json_decode($test_var_str2);
echo ("var_dump test_var_str2_json_decoded<br>");
var_dump($test_var_str2_json_decoded);



?>