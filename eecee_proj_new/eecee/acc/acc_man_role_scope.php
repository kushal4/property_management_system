<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $eecee_php_lib_path.'eecee_lib.php';
include $eecee_php_lib_path.'eecee_sec_map.php';

//include 'sec.php';
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
addStyleSheet($doc_head, $eecee_ext_styles_path."jquery-ui.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."themes/default/style.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."/themes/default-dark/style.css");

addStyleSheet($doc_head, $eecee_styles_path."home.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."vert_tab.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."style_test03.css?".time());
// CSS Files :: END

// JS Files :: START
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-3.2.1.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-ui.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jstree.js");
addScriptPath($doc_head, $sense_common_js_lib_path."resize.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."sense-lib.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."table_creator.js?".time());

addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); 
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."accordion.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."common_js_file.js?".time());

addScriptPath($doc_head, "acc_man_role_scope.js?".time());
// JS Files :: END

$acc_man_role_assign_parent_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");
//$features_pg_sec = insertPageSection($acc_man_role_assign_parent_container, '', '{"class":"sel-prop-page-section"}');

$role_scope_accordion = new jqueryui_accordion_widget("select_role_accordion");
$role_scope_accordion->addClass("genereic_accordion");
$role_scope_accordion->setParent($acc_man_role_assign_parent_container);

/*BEGINNING of selected Role tab*/
$role_scope_accordion->insertTab("selectedrole","Selected Role: None");
$selected_role_view = $role_scope_accordion->addViewtoTabContainer("selectedrole","selected_role_view");
$selected_role_view_ps1 = insertPageSection  ($selected_role_view, '', '{"class":"page-section"}');
$selected_role_view_container = insertElement($selected_role_view_ps1,"div",'{"id": "selected_role_view_container","class":"selected_role_view_container_style"}'," ");
$role_scope_role_tree = insertElement($selected_role_view_container,"div",'{"class":""}'," ");  
addAttribToElement($role_scope_role_tree, '{"id":"role_scope_role_tree_acc"}');
/*END of selected Role tab*/

/*BEGINNING of selected Role tab*/
$role_scope_accordion->insertTab("something","Something");
$selected_role_view = $role_scope_accordion->addViewtoTabContainer("something","Something_view");
$selected_role_view_ps1 = insertPageSection  ($selected_role_view, '', '{"class":"something"}');

$acc_man_role_assign_left_container = insertElement($selected_role_view_ps1,"div",'{"id": "acc_man_role_assign_left_container" ,"class":"role_scope_vertical_split left_split_style"}'," ");
$role_scope_role_tree = insertElement($acc_man_role_assign_left_container,"div",'{"class":""}'," ");  
addAttribToElement($role_scope_role_tree, '{"id":"role_scope_role_tree"}');


$mid_btn_container = insertElement($selected_role_view_ps1,"div",'{"id": "mid_btn", "class":"mid_btn_style"}'," ");
$btn_container = insertElement($mid_btn_container,"button",'{"id":"mid_button", "disabled": "disabled"}',"=>");  


$acc_man_role_assign_right_container = insertElement($selected_role_view_ps1,"div",'{"id": "acc_man_role_assign_right_container", "class":"role_scope_vertical_split"}'," ");
$dym_role_scope_role_tree = insertElement($acc_man_role_assign_right_container,"div",'{"class":""}'," ");  
addAttribToElement($dym_role_scope_role_tree, '{"id":"dym_role_scope_tbl"}');


///////////////////////////////////////////////////////////////
////////START OF DIALOGUES
///////////////////////////////////////////////////////////////
$dialog_parent = $doc_body;



//====================== update role/ category dialog ends======================
///////////////////////////////////////////////////////////////
////////END OF DIALOGUES
///////////////////////////////////////////////////////////////

echo $doc->saveHTML();

$logfile->logfile_close();
?>


<?php

?>




