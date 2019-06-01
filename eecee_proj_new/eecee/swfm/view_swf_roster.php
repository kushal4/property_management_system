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
$html.="    <div id=\"roster_view_main_cont\">";
$html.="    </div>";
$html.="</body>";
$html.="</html>";


$doc = createDomDoc($html);
$main_container =$doc->getElementById('roster_view_main_cont');




$swf_roster_list = insertElement($main_container,"div",'{"id":"swf_roster_container_list", "class":"unit_type_list_style"}'," ");
$feat_list_pg_sec = insertPageSection  ($swf_roster_list, '', '{"class":"container_body_pg_sec_style"}');

//$create_btn_cont = insertElement($feat_list_pg_sec,"div",'{"id":"create_btn_cont", "class":"create_btn_cont_style"}'," ");
//$create_btn_span = insertElement($create_btn_cont,"span",'{"id":"create_btn_span", "class":"create_btn_span_style"}',"Create New Unit Type");

$feat_table_container = insertPanel($feat_list_pg_sec,'',"");
$feat_table_container->setAttribute('id',"setup_prop_swf_tbl_cont");

//$feat_table = new sense_table("test_tbl");
$feat_table = new sense_table([
                                //'id'=>'test_tbl',
                                'widgetStyle'=>'',
                                'headingStyle'=>'heading_sty',
                                'headingText' => 'Test Table Heading Text',
                                'headingTextStyle' => 'heading-text-sty',
                                'contentTableStyle' => ' generic_table swfm_tble_style',
                                'contentStyle' => 'unit_type_feat_tbl_sty'
]);


//$feat_table->setAttrib('{"class":"generic_table"}');
$the_table = $feat_table->setParent($feat_table_container);

$row_obj = $feat_table->addRow();
$feat_table->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $feat_table->addCell("", TRUE);
$feat_table->setCurrCellID("cell_1");

//$feature_heading_parent_cont = insertElement($cell_obj,"div",'{"id":"feature_heading_parent_cont", "class":"feature_heading_parent_cont_style"}'," ");
$feature_heading = insertElement($cell_obj,"div",'{"id":"feature_heading", "class":"feature_heading_style"}',"");
$add_feature_name_plus = insertElement($feature_heading,"span",'{"id":"add_feature_name_plus", "class":"add_feature_name_plus_style"}',"SWF Users");

$add_feature_plus_cont = insertElement($cell_obj,"div",'{"id":"add_swf_roster_plus_cont", "class":"add_feature_plus_cont_style"}'," ");
//$add_feature_plus = insertElement($cell_obj,"span",'{"id":"add_feature_plus", "class":"add_feature_plus_style"}',"");
$add_feature_plus = insertElement($add_feature_plus_cont,"img",'{"id":"add_feature_plus", "src":"../themes/images/plus_1_16x16.png"}',"");
//src=\"../themes/images/userlogo.png\"

// $row_obj = $feat_table->addRow();
// $feat_table->setRowID($row_obj, "row_".$feat_id);
// addAttribToElement($row_obj, '{"class":""}');
// addAttribToElement($row_obj, '{"data-prop_id": '.$feat_id.' }');

// // first column starts
// $cell_obj = $feat_table->addCell("");
// $feat_table->setCurrCellID("cell".$secedFeatId."_1");
// addAttribToElement($cell_obj, '{"class":"Feature_list_name_style"}');
//$cell_obj = $feat_table->addCell("", TRUE);
//$feat_table->setCurrCellID("cell_2");



echo $doc->saveHTML();

$logfile->logfile_close();


?>