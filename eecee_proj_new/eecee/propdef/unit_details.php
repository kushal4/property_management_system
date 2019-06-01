<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
$session_val= is_session_valid();
if($session_val==0){
}
else{
    header("Location: eecee_login.php");
}

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

include $sense_common_php_lib_path.'master_layout.php';

addIcon($doc_head, "/favicon.ico");

// CSS Files :: START
addStyleSheet($doc_head, $eecee_styles_path."home.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."vert_tab.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."style_test03.css?".time());

addStyleSheet($doc_head, $eecee_ext_styles_path."jquery-ui.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."themes/default/style.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."/themes/default-dark/style.css");
// CSS Files :: END

// JS Files :: START
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-3.2.1.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-ui.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jstree.js");
addScriptPath($doc_head, $sense_common_js_lib_path."resize.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."sense-lib.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."table_creator.js?".time());

addScriptPath($doc_head, $eecee_js_lib_path."accordion.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); 
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());
addScriptPath($doc_head, "prop_def.js?".time());

addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."common_js_file.js?".time());

//addScriptPath($doc_head, "lib/js-lib/dashboard.js?".time());
//addScriptPath($doc_head, "../lib/js-lib/Chart.js?".time());


///////////////////////////////////////////////////////////////
////////MAIN LAYOUT CONTENT: START
///////////////////////////////////////////////////////////////

$unit_id = $SENSESSION->get_val("unit_id");

//echo "the unit id is:".$unit_id."</br>";

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$check_user_prop_topo = "SELECT * FROM prop_topo WHERE id = ?";
$check_user_prop_topo_temp = $conn->prepare($check_user_prop_topo);
$check_user_prop_topo_temp->bind_param("i",$unit_id);
$check_user_prop_topo_temp->execute();
$check_user_prop_topo_temp_result = $check_user_prop_topo_temp->get_result();
$prop_topo_temp_row = $check_user_prop_topo_temp_result->fetch_assoc();
$unit_name = $prop_topo_temp_row['node_name'];
$prop_id = $prop_topo_temp_row['prop_id'];

//echo "the unit name is::".$unit_name."</br>";
//echo "the prop ID is::".$prop_id."</br>";


$check_properties = "SELECT * FROM properties WHERE id = ?";
$check_properties_temp = $conn->prepare($check_properties);
$check_properties_temp->bind_param("i",$prop_id);
$check_properties_temp->execute();
$check_properties_temp_result = $check_properties_temp->get_result();
$properties_row = $check_properties_temp_result->fetch_assoc();
$prop_name = $properties_row['setup_name'];

//echo "the property name is:: ".$prop_name."</br>";

$unit_dets_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
//$unit_dets_pg_sec = insertPageSection  ($unit_dets_container, '', '{"class":"sel-prop-page-section"}');
$unit_dets_pg_sec = insertElement($unit_dets_container,"div",'{"class":"unit_dets_pg_sec_style"}'," "); 

$header_parent_cont = insertElement($unit_dets_pg_sec,"div",'{"class":"header_parent_cont_style"}'," "); 
$property_name_cont = insertElement($header_parent_cont,"div",'{"class":"property_name_cont_style"}'," "); 
$property_name_span = insertElement($property_name_cont,"span",'{"class":"property_name_styles"}', "Property name: ".$prop_name);
$unit_name_cont = insertElement($header_parent_cont,"div",'{"class":"unit_name_cont_style"}'," "); 
$unit_name_span = insertElement($unit_name_cont,"span",'{"class":"property_name_styles"}', "Unit name: ".$unit_name);



$parent_container = insertElement($unit_dets_pg_sec,"div",'{"class":"parent_container_style"}'," "); 

$flat_dets_container = insertElement($parent_container,"div",'{"id":"flat_dets_container", "class":"flat_dets_container_style"}',""); 
$flat_dets_span = insertElement($flat_dets_container,"h3",'{"class":"flat_dets_span_styles"}', "Flat Details");

$users_container = insertElement($parent_container,"div",'{"id":"users_container", "class":"users_container_style"}',"");
$users_span = insertElement($users_container,"h3",'{"class":"users_span_styles"}', "Users");
///////////////////////////////////////////////////////////////
////////START OF DIALOGUES
///////////////////////////////////////////////////////////////
$dialog_parent = $doc_body;


///////////////////////////////////////////////////////////////
////////END OF DIALOGUES
///////////////////////////////////////////////////////////////

echo $doc->saveHTML();

$logfile->logfile_close();
?>



