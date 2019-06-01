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

$request_val=$_POST["q"];
$prop_id=$_SESSION["prop_id"];
if($request_val=="l"){
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


  
    $header_section=insertElement($unit_type_list_pg_sec,"div",'{"id":"header_section", "class":"unit_type_edit_style"}'," ");
    $header=insertElement($header_section,"h1",'{"class":"unit_type_edit_style"}',"Manage issue priorities");
    $button_section=insertElement($unit_type_list_pg_sec,"div",'{"id":"button_section", "class":"unit_type_edit_style"}'," ");
    $button=insertElement($button_section,"button",'{"id":"create_priority", "class":"unit_type_edit_style"}',"Create Priority");
    $table_section=insertElement($unit_type_list_pg_sec,"div",'{"id":"table_section", "class":"unit_type_edit_style"}'," ");
    
  
$prop_types_sql = "SELECT * FROM hesk_priorities WHERE prop_id = ? order by order_hesk";
$prop_types_temp = $conn->prepare($prop_types_sql);
$prop_types_temp->bind_param("i",$prop_id);
$prop_types_temp->execute();
$prop_types_result = $prop_types_temp->get_result();
$prop_types_fetch_all = $prop_types_result->fetch_all(MYSQLI_ASSOC);
$row_count=mysqli_num_rows($prop_types_result);
//echo 'ROW count='.$row_count;
if($row_count>0){

    $unit_type_table = new sense_table([
        'id'=>'test_tbl',
        'widgetStyle'=>'wdg_sty',
        'headingStyle'=>'heading_sty',
        'headingText' => 'Test Table Heading Text',
        'headingTextStyle' => 'heading-text-sty',
        'contentTableStyle' => 'generic_table',
        'contentStyle' => 'unit_type_table_wrapper'
]);


//$unit_type_table->setAttrib('{"class":"generic_table"}');

$the_table = $unit_type_table->setParent($table_section);

$row_obj = $unit_type_table->addRow();
$unit_type_table->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $unit_type_table->addCell("Issue Type", TRUE);
$unit_type_table->setCurrCellID("cell_1");

$cell_obj = $unit_type_table->addCell("Actions", TRUE);
$unit_type_table->setCurrCellID("cell_2");

sec_clear_map ("issue_prio_map");
//$prop_id = $_SESSION["prop_id"];
    foreach ($prop_types_fetch_all as $v){
        $id=$v["id"];
        $name=$v["name"];

        $secedFeatCatSig = sec_push_val_single_entry ("issue_prio_map", $id);
        $row_obj = $unit_type_table->addRow();
        $unit_type_table->setRowID($row_obj, "row_".$id);
        addAttribToElement($row_obj, '{"class":""}');
       // addAttribToElement($row_obj, '{"data-prop_id": '.$id.' }');

        $cell_obj = $unit_type_table->addCell($name);
        $unit_type_table->setCurrCellID("cell".$secedFeatCatSig."_1");
        addAttribToElement($cell_obj, '{"class":"Feature_name_style"}');
        addDataToElement($cell_obj, '{"s":"'.$secedFeatCatSig.'"}');

        $cell_obj = $unit_type_table->addCell("");
        $unit_type_table->setCurrCellID("cell".$secedFeatCatSig."_2");
        addAttribToElement($cell_obj, '{"class":"Feature_name_style"}');
        addDataToElement($cell_obj, '{"s":"'.$secedFeatCatSig.'"}');
        $update_button=insertElement($cell_obj,"button",'{"class":"hesk_update"}',"Update");
        $delete_button=insertElement($cell_obj,"button",'{"class":"hesk_del"}',"Delete");
        $up_btn=insertElement($cell_obj,"button",'{"class":"up"}',"up");
        $down_btn=insertElement($cell_obj,"button",'{"class":"down"}',"down");




    }

}

}else if($request_val=="c"){
    $text_box=insertElement($unit_type_list_pg_sec,"input",'{"id":"cat_txt", "class":""}',"");
    $button=insertElement($unit_type_list_pg_sec,"button",'{"id":"cat_add_btn", "class":""}',"Create");
}
else if($request_val=="u"){
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sec_val=$_POST["s"];
     $id = sec_get_map_val ("issue_prio_map", $sec_val);

    $prop_types_sql = "SELECT * FROM hesk_priorities WHERE id = ?";
    $prop_types_temp = $conn->prepare($prop_types_sql);
    $prop_types_temp->bind_param("i",$id);
    $prop_types_temp->execute();
    $prop_types_result = $prop_types_temp->get_result();
    $row=$prop_types_result->fetch_assoc();
    $text_box=insertElement($unit_type_list_pg_sec,"input",'{"id":"cat_txt", "class":"","value":"'. $row["name"].'"}',"test");
    $button=insertElement($unit_type_list_pg_sec,"button",'{"id":"cat_update_btn", "class":""}',"Update");
    addDataToElement($button, '{"s":"'.$sec_val.'"}');
}



//php_info();
echo $doc->saveHTML();

?>