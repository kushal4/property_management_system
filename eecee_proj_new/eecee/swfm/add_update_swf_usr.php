<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."swfm.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'sense_lib.php';
include $sense_common_php_lib_path.'session_exp.php';
include $eecee_php_lib_path.'eecee_lib.php';
include $eecee_php_lib_path.'eecee_sec_map.php';
include $sense_common_php_lib_path.'actl_lib.php';
include "swf_user_roster_inp.php";

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
$html.="    <div id=\"add_roster_user_main_cont\">";
$html.="    </div>";
$html.="</body>";
$html.="</html>";


$doc = createDomDoc($html);
$main_container =$doc->getElementById('add_roster_user_main_cont');




$swf_roster_list = insertElement($main_container,"div",'{"id":"swf_roster_container_list", "class":"unit_type_list_style"}'," ");
$feat_list_pg_sec = insertPageSection  ($swf_roster_list, '', '{"class":"container_body_pg_sec_style"}');

//$create_btn_cont = insertElement($feat_list_pg_sec,"div",'{"id":"create_btn_cont", "class":"create_btn_cont_style"}'," ");
//$create_btn_span = insertElement($create_btn_cont,"span",'{"id":"create_btn_span", "class":"create_btn_span_style"}',"Create New Unit Type");

$feat_table_container = insertPanel($feat_list_pg_sec,'',"");
$feat_table_container->setAttribute('id',"add_updte_swf_usr_tbl_cont");
$opcode=$_POST["op"];
if($opcode=="n"){
    //$feat_table = new sense_table("test_tbl");
    $feat_table = new sense_table([
                                    //'id'=>'test_tbl',
                                    'widgetStyle'=>'',
                                    'headingStyle'=>'heading_sty',
                                    'headingText' => 'Test Table Heading Text',
                                    'headingTextStyle' => 'heading-text-sty',
                                    'contentTableStyle' => 'swfm_tble_style',
                                    'contentStyle' => 'unit_type_feat_tbl_sty'
    ]);
    
    
    //$feat_table->setAttrib('{"class":"generic_table"}');
    roster_view_create($feat_table);
    $the_table = $feat_table->setParent($feat_table_container); 
    
}

echo $doc->saveHTML();

//$feat_table->setAttrib('{"class":"generic_table"}');
//$the_table = $feat_table->setParent($feat_table_container);

?>