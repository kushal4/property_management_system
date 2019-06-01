<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'sec.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
$html.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
$html.="<html>";
$html.="<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">";
$html.="<body>";
$html.="    <div id=\"main_cont\">";
$html.="    </div>";
$html.="</body>";
$html.="</html>";


$doc = createDomDoc($html);
$main_container =$doc->getElementById('main_cont');

$unit_type_edit = insertElement($main_container,"div",'{"id":"unit_type_edit", "class":"unit_type_edit_style"}'," ");
$unit_type_edit_pg_sec = insertPageSection  ($unit_type_edit, '', '{"class":"container_body_pg_sec_style"}'); // the page section element

// 1st DIV starts here
$add_unit_type_name_cont = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_type_name_cont", "class":"add_unit_type_name_cont_style"}'," ");

/*
$add_unit_tbl_parent = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_tbl_parent", "class":"add_unit_tbl_parent_style_sess"}'," ");
$unit_type_name_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"unit_type_name_cont", "class":"unit_type_name_cont_style"}'," ");
$unit_type_name_span = insertElement($unit_type_name_cont,"span",'{"id":"unit_type_name_span", "class":"unit_type_name_span_style"}',"Unit Type Name2: ");
$txtbox_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"txtbox_cont", "class":"txtbox_cont_style"}'," ");
$unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"$unit_type_name\"}";
$unit_type_name_txtbox = insertElement($txtbox_cont,"input","$unit_type_name_prop","");

$edit_btn_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"save_btn_cont", "class":"edit_btn_cont_style", "op":"e"}',"");
$edit_btn_span = insertElement($edit_btn_cont,"span",'{"id":"edit_btn_span", "class":"edit_btn_span_style"}',"Save");
*/
// 1st DIV ends here

// 2nd DIV starts here
$feature_tbl_cont = insertElement($unit_type_edit_pg_sec,"div",'{"id":"edit_feature_tbl_cont", "class":"feature_tbl_cont_style"}'," "); // the feature list table div
// 2nd DIV ends here

// 3rd DIV starts here
$add_unit_feature_cont = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_feature_cont", "class":"add_unit_feature_cont_style"}'," ");


$add_unit_feature_btn_div = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_feature_btn_div", "class":"add_unit_feature_btn_div_style"}'," ");
//$add_unit_feature_btn_span = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_btn_span", "class":"add_unit_feature_btn_span_style"}',"Add Unit Feature");
//$add_unit_feature_plus_cont = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_plus_cont", "class":"add_unit_feature_plus_cont_style"}'," ");
//$add_unit_feature_plus = insertElement($add_unit_feature_plus_cont,"div",'{"id":"add_unit_feature_plus", "class":"add_unit_feature_plus_style"}',"+");

// 3rd DIV ends here   

echo $doc->saveHTML();

$logfile->logfile_close();


?>