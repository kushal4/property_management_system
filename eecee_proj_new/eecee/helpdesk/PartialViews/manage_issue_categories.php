<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
//include '../../lib/php-lib/eecee_constants.php';
include  $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'reg_func.php';
include $sense_common_php_lib_path.'sec.php';
session_start();

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

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$doc = createDomDoc($html);

    $main_container =$doc->getElementById('main_cont');
    $main_div = insertElement($main_container,"div",'{"id":"content", "class":"unit_type_edit_style"}'," ");
    

  
    $header_section=insertElement($main_div,"div",'{"id":"header_section", "class":"unit_type_edit_style"}'," ");
    $header=insertElement($header_section,"h1",'{"class":"unit_type_edit_style"}',"Manage issue priorities");
    $button_section=insertElement($main_div,"div",'{"id":"button_section", "class":"unit_type_edit_style"}'," ");
    $button=insertElement($button_section,"button",'{"id":"create_priority", "class":"unit_type_edit_style"}',"Create Priority");
    $table_section=insertElement($main_div,"div",'{"id":"table_section", "class":"unit_type_edit_style"}'," ");
    
  /*
$prop_types_sql = "SELECT * FROM hesk_priorities WHERE prop_id = ?";
$prop_types_temp = $conn->prepare($unit_types_sql);
$prop_types_temp->bind_param("i",$prop_id);
$prop_types_temp->execute();
$prop_types_result = $prop_types_temp->get_result();
$prop_types_fetch_all = $prop_types_result->fetch_all(MYSQLI_ASSOC);
$row_count=mysqli_num_rows($prop_types_result);
if($row_count>0){
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
$cell_obj = $unit_type_table->addCell("Issue Type", TRUE);
$unit_type_table->setCurrCellID("cell_1");

$cell_obj = $unit_type_table->addCell("Actions", TRUE);
$unit_type_table->setCurrCellID("cell_2");
$prop_id = $_SESSION["prop_id"];

}

*/

//php_info();
echo $doc->saveHTML();

?>