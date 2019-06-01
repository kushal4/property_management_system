<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
//include '../eecee_include.php';
include '../eecee_include.php';
//include $eecee_php_lib_path.'eecee_sec_map.php';
//include '../../lib/php-lib/eecee_constants.php';
include  $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'reg_func.php';
include $sense_common_php_lib_path.'sec.php';



$id=$_SESSION["prop_id"];
//echo "Prop id=".$id;
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
$main_div = insertElement($main_container,"div",'{"id":"content", "class":"unit_type_edit_style"}'," ");
$unit_type_list_pg_sec = insertPageSection  ($main_div, '', '{"class":"container_body_pg_sec_style"}');



  
    $header_section=insertElement($unit_type_list_pg_sec,"div",'{"id":"header_section", "class":"unit_type_edit_style"}'," ");
    $header=insertElement($header_section,"h1",'{"class":"unit_type_edit_style"}',"Manage issue Categories");
    $button_section=insertElement($unit_type_list_pg_sec,"div",'{"id":"cat_tree", "class":"unit_type_edit_style"}'," ");
   


echo $doc->saveHTML();
?>