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

//include '../lib/php-lib/actl_lib.php';
//include 'eecee_dom_layout.php';
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


//$feature_tbl_cont = insertElement($main_container,"div",'{"id":"test_test", "class":"feature_tbl_cont_style"}'," ");
/*
$feature_tbl = insertElement($main_container,"div",'{"id":"feature_tbl", "class":"feature_tbl_style"}'," ");

$feature_table_container = insertPanel($feature_tbl_cont,'',"");
$feature_table_container->setAttribute('id',"setup_prop_tbl_container");


$feature_table = new sense_table([
                                //'id'=>'test_tbl',
                                'widgetStyle'=>'wdg_sty',
                                'headingStyle'=>'heading_sty',
                                'headingText' => 'Test Table Heading Text',
                                'headingTextStyle' => 'heading-text-sty',
                                'contentTableStyle' => 'generic_table',
                                'contentStyle' => 'unit_type_table_wrapper'
]);

$the_table = $feature_table->setParent($feature_table_container);

$row_obj = $feature_table->addRow();
$feature_table->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $feature_table->addCell("Feature", TRUE);
$feature_table->setCurrCellID("cell_1");

$cell_obj = $feature_table->addCell("Actions", TRUE);
$feature_table->setCurrCellID("cell_2");
   */ 

$prop_id = $SENSESSION->get_val("prop_name");
//echo $prop_id;
//echo "ff".$unit_type_id;
$unit_type_id = $SENSESSION->get_val("unit_type_id");
if($unit_type_id != null){

//echo "over here";

//insertElement($main_container,"span",'{"id":"test_test", "class":"feature_tbl_cont_style"}'," test_span");
/*
$feature_tbl = insertElement($main_container,"div",'{"id":"feature_tbl", "class":"feature_tbl_style"}'," ");

$feature_table_container = insertPanel($feature_tbl_cont,'',"");
$feature_table_container->setAttribute('id',"setup_prop_tbl_container");


$feature_table = new sense_table([
                                //'id'=>'test_tbl',
                                'widgetStyle'=>'wdg_sty',
                                'headingStyle'=>'heading_sty',
                                'headingText' => 'Test Table Heading Text',
                                'headingTextStyle' => 'heading-text-sty',
                                'contentTableStyle' => 'generic_table',
                                'contentStyle' => 'unit_type_table_wrapper'
]);

$the_table = $feature_table->setParent($feature_table_container);

$row_obj = $feature_table->addRow();
$feature_table->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $feature_table->addCell("Feature", TRUE);
$feature_table->setCurrCellID("cell_1");

$cell_obj = $feature_table->addCell("Actions", TRUE);
$feature_table->setCurrCellID("cell_2");
*/
/*

$conn = new \mysqli($server_name, $user_name, $password, $dbname);

    //echo "the database name is::".$dbname;
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $unit_type_fea_sql = "SELECT * FROM unit_type_fea WHERE prop_id = ? and unit_type_id = ?";
    $unit_type_fea_temp = $conn->prepare($unit_type_fea_sql);
    $unit_type_fea_temp->bind_param("ii",$prop_id, $unit_type_id);
    $unit_type_fea_temp->execute();
    $unit_type_fea_result = $unit_type_fea_temp->get_result();
    $unit_type_fea_fetch_all = $unit_type_fea_result->fetch_all(MYSQLI_ASSOC);
    
    foreach ($gen_result_row as $value){  
        $unit_fea_id = $value["unit_fea_id"];
        
        $unit_fea_sql = "SELECT * FROM unit_features WHERE id = ?";
        $unit_fea_temp = $conn->prepare($unit_fea_sql);
        $unit_fea_temp->bind_param("i",$unit_fea_id);
        $unit_fea_temp->execute();
        $unit_fea_result = $unit_fea_temp->get_result();
        $unit_type_fea_fetch_assoc = $unit_fea_result->fetch_assoc(MYSQLI_ASSOC);
        $feat_name = $unit_type_fea_fetch_assoc["name"];
        $feat_id = $unit_type_fea_fetch_assoc["id"];

        $cell_obj = $feature_table->addCell($feat_name);
        $feature_table->setCurrCellID("cell".$feat_id."_1");
       //addAttribToElement($cell_obj, '{"class":"prp_name_style"}');
       //addAttribToElement($cell_obj, '{"data-prop_id": '.$prop_id.' }');

    }
*/

}else{
    //echo "here";
    $feature_span_cont = insertElement($main_container,"div",'{"id":"feature_span_cont", "class":"feature_span_cont_style"}'," ");
    $feature_span = insertElement($main_container,"div",'{"id":"feature_span", "class":"feature_span_style"}'," ");
}


?>