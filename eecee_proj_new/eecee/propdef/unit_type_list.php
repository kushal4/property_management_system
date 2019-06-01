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


$unit_type_list = insertElement($main_container,"div",'{"id":"unit_type_list", "class":"unit_type_list_style"}'," ");
$unit_type_list_pg_sec = insertPageSection  ($unit_type_list, '', '{"class":"container_body_pg_sec_style"}');

$create_btn_cont = insertElement($unit_type_list_pg_sec,"div",'{"id":"create_btn_cont", "class":"create_btn_cont_style"}'," ");
$create_btn_span = insertElement($create_btn_cont,"span",'{"id":"create_btn_span", "class":"create_btn_span_style"}',"Create New Unit Type");

$unit_type_table_container = insertPanel($unit_type_list_pg_sec,'',"");
$unit_type_table_container->setAttribute('id',"setup_prop_tbl_container");

//$unit_type_table = new sense_table("test_tbl");
$unit_type_table = new sense_table([
                                //'id'=>'test_tbl',
                                'widgetStyle'=>'wdg_sty',
                                'headingStyle'=>'heading_sty',
                                'headingText' => 'Test Table Heading Text',
                                'headingTextStyle' => 'heading-text-sty',
                                'contentTableStyle' => 'generic_table',
                                'contentStyle' => 'unit_type_table_wrapper'
]);


//$unit_type_table->setAttrib('{"class":"generic_table"}');

$the_table = $unit_type_table->setParent($unit_type_table_container);

$row_obj = $unit_type_table->addRow();
$unit_type_table->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $unit_type_table->addCell("Unit Type", TRUE);
$unit_type_table->setCurrCellID("cell_1");

$cell_obj = $unit_type_table->addCell("Actions", TRUE);
$unit_type_table->setCurrCellID("cell_2");

$prop_id = $SENSESSION->get_val("prop_id");
$logfile->logfile_writeline("The prop_id is :: ".$prop_id);


$unit_types_sql = "SELECT * FROM unit_types WHERE prop_id = ?";
$unit_types_temp = $conn->prepare($unit_types_sql);
$unit_types_temp->bind_param("i",$prop_id);
$unit_types_temp->execute();
$unit_types_result = $unit_types_temp->get_result();
$unit_types_fetch_all = $unit_types_result->fetch_all(MYSQLI_ASSOC);

$unit_types_fetch_all_str = var_export($unit_types_fetch_all, true);
$logfile->logfile_writeline("The unit_types_fetch_all_str is :: ".$unit_types_fetch_all_str);

sec_clear_map ("unit_type_id_sig_map");

foreach ($unit_types_fetch_all as $v){
    $unit_type_id = $v["id"];
    $unit_type_name = $v["name"];

    $logfile->logfile_writeline("The the unit type id is :: ".$unit_type_id);
    $logfile->logfile_writeline("The the unit type name is :: ".$unit_type_name);

    $secedUnitTypeId = sec_push_val_single_entry ("unit_type_id_sig_map", $unit_type_id);

    $row_obj = $unit_type_table->addRow();
    $unit_type_table->setRowID($row_obj, "row_".$sig);
    addAttribToElement($row_obj, '{"class":""}');
    addAttribToElement($row_obj, '{"data-prop_id": '.$sig.' }');

    $cell_obj = $unit_type_table->addCell($unit_type_name);
    $unit_type_table->setCurrCellID("cell".$secedUnitTypeId."_1");
    addAttribToElement($cell_obj, '{"class":"unit_type_name_style"}');

    $cell_obj = $unit_type_table->addCell("");
    $unit_type_table->setCurrCellID("cell".$secedUnitTypeId."_2");
    addAttribToElement($cell_obj, '{"class":"unit_type_name_action_style"}');
    $update_cont = insertElement($cell_obj,"div",'{"id":"update_cont", "class":"update_cont_class"}',"");
    $update_span = insertElement($update_cont,"span",'{"id":"update_span", "class":"update_span_class"}',"Update");
    addDataToElement($update_span, '{"ut":"'.$secedUnitTypeId.'"}');

    $delete_cont = insertElement($cell_obj,"div",'{"id":"delete_cont", "class":"delete_cont_class"}',"");
    $delete_span = insertElement($delete_cont,"span",'{"id":"delete_span", "class":"delete_span_class"}',"Delete");
    addDataToElement($delete_span, '{"ut":"'.$secedUnitTypeId.'"}');
    //addAttribToElement($cell_obj, '{"data-prop_id": '.$sig.' }');
    //$lower_sig = strtolower($sig);
    //echo "the lower sig is".$lower_sig."</br>";
    //addDataToElement($cell_obj, '{"j":"'.$lower_sig.'"}');
    //addDataToElement($cell_obj, '{"s":"'.$secedUnitTypeId.'"}');

    /*
    $curr_map = sec_get_map("unit_type_id_sig_map");
    
    $logfile->logfile_writeline(__FILE__."---Dumping unit_type_id_sig_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."---Dumping unit_type_id_sig_map MAP: End");
    */
}


echo $doc->saveHTML();

$logfile->logfile_close();


?>