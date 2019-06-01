<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path . "swfm1.log";
require_once $sense_common_php_lib_path . 'Log.php';

include $eecee_php_lib_path . 'eecee_lib.php';
include $sense_common_php_lib_path . 'actl_lib.php';
include $sense_common_php_lib_path . 'sec.php';
include $sense_common_php_lib_path . 'session_exp.php';
$actlGetRoleTreeCurlURL = $actl_urls->actlGetRoleTreeCurlURL;
$role_sig = "FRONTENDSUPERADMIN";
$role_sig = $SENSESSION->get_val("role_sig");
$prop_id = $SENSESSION->get_val("prop_id");
//$swf_project_id="8YEYOZr9vs9yzf4GyvKfu7tnXRyiney1"
$data = array("param" => array("key" => $projectID, "client_id" => $prop_id, "role_sig" => $role_sig));

$data_str = json_encode($data, JSON_UNESCAPED_SLASHES);
$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");
$data_str = json_encode($data, JSON_UNESCAPED_SLASHES);
//echo "data_str=".$data_str."<br>";
// $logfile->logfile_writeline("To Curl: ".$data_str);
$actlRetObj = CurlSendPostJson($actlGetRoleTreeCurlURL, $data_str, $logfile);
$logfile->logfile_writeline("From Curl: ".print_r($actlRetObj,true));
?>
`