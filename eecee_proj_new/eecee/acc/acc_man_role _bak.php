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

//include 'lib/php-lib/eecee_constants.php';
//include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
//include 'lib/php-lib/common_functions.php';
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
addStyleSheet($doc_head, $eecee_styles_path."home.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."vert_tab.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."style_test03.css?".time());

addStyleSheet($doc_head, $eecee_ext_styles_path."jquery-ui.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."themes/default/style.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."/themes/default-dark/style.css");
// CSS Files :: END

//JS Files :: START
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-3.2.1.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-ui.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jstree.js");
addScriptPath($doc_head, $sense_common_js_lib_path."resize.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."sense-lib.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."table_creator.js?".time());

addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); 
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."common_js_file.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."accordion.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, "acc_man_role.js?".time());
//addScriptPath($doc_head, "lib/js-lib/accordion.js?".time());
//addScriptPath($doc_head, "lib/js-lib/dashboard.js?".time());
//addScriptPath($doc_head, "../lib/js-lib/Chart.js?".time());
//addScriptPath($doc_head, "../lib/js-lib/table_creator.js?".time());

$acc_man_role_parent_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");
$features_pg_sec = insertPageSection($acc_man_role_parent_container, '', '{"class":"sel-prop-page-section"}');
$role_tree_container = insertElement($features_pg_sec,"div",'{"class":"user_prop_container"}'," ");  
addAttribToElement($role_tree_container, '{"id":"roll_tree"}');
//$test_btn = insertElement($features_pg_sec,"button",'{"id":"test_btn"}',"test");  


///////////////////////////////////////////////////////////////
////////START OF DIALOGUES
///////////////////////////////////////////////////////////////
$dialog_parent = $doc_body;

$cant_del_role_cat_dialog = insertPanel($dialog_parent, '{"id":"cant_del_role_cat_dialog", "class":"", "title":" "}',"");
$dialog_parent_cont = insertElement($cant_del_role_cat_dialog,"div",'{"class":"dialog_parent_cont_style"}', "");
addAttribToElement($dialog_parent_cont, '{"id":"dialog_parent_cont"}');

$dialog_msg_cont = insertElement($dialog_parent_cont,"div",'{"class":"dialog_msg_cont_style"}', "");
$dialog_msg_span = insertElement($dialog_msg_cont,"span",'{"class":"heading_styles"}', "Can't delete this Role Category. It contains the following roles.");

$dialog_ul_parent_cont = insertElement($dialog_parent_cont,"div",'{"class":""}', "");
$dialog_ul_cont = insertElement($dialog_ul_parent_cont,"div",'{"class":"no_del_ul_cont_style"}', "");
addAttribToElement($dialog_ul_cont, '{"id":"no_del_ul_cont"}');

$dialog_msg2_cont = insertElement($dialog_parent_cont,"div",'{"class":"dialog_msg_cont_style"}', "");
$dialog_msg_span = insertElement($dialog_msg2_cont,"span",'{"class":"heading_styles"}', "Please delete these roles first and try to delete the role category once again.");

//====================== update role/ category dialog starts======================
$upd_role_n_cat_dialog = insertPanel($dialog_parent, '{"id":"upd_role_n_cat_dialog", "class":"upd_role_n_cat_dialog_style", "title":" Update Role"}',"");
$upd_role_n_cat_parent_cont = insertElement($upd_role_n_cat_dialog,"div",'{"class":"upd_role_n_cat_parent_cont_style"}', "");
addAttribToElement($upd_role_n_cat_parent_cont, '{"id":"upd_role_n_cat_parent_cont"}');

/*
$name_parent_cont = insertElement($upd_role_n_cat_parent_cont,"div",'{"id":"name_parent_cont","class":"name_parent_cont_style"}', ""); //rename node div
$name_span_cont = insertElement($name_parent_cont,"div",'{"id":"name_span_cont","class":"name_span_cont_style"}', "");
$name_span = insertElement($name_parent_cont,"span",'{"id":"name_span","class":"name_span_style"}', "Update Name");
$name_textbox_cont = insertElement($name_parent_cont,"div",'{"id":"name_textbox_cont","class":"name_textbox_cont_style"}', "");
$name_textbox = insertElement($name_parent_cont,"input",'{"id":"name_textbox","class":"name_textbox_style", "type":"text"}', "");

$description_parent_cont = insertElement($upd_role_n_cat_parent_cont,"div",'{"id":"description_parent_cont","class":"description_parent_cont_style"}', ""); //description node div
$description_span_cont = insertElement($description_parent_cont,"div",'{"id":"description_span_cont","class":"description_span_cont_style"}', "");
$description_span = insertElement($description_parent_cont,"span",'{"id":"description_span","class":"description_span_style"}', "Update Description");
$description_textbox_cont = insertElement($description_parent_cont,"div",'{"id":"description_textbox_cont","class":"description_textbox_cont_style"}', "");
$description_textbox = insertElement($description_parent_cont,"textarea",'{"id":"description_textbox","class":"description_textbox_style", "maxlength":"20"}', "");

*/
$upd_role_n_cat_table = insertElement($upd_role_n_cat_parent_cont,"table",'{"class":"upd_role_n_cat_parent_cont_style"}', "");
$upd_role_n_cat_tbl_name_tr = insertElement($upd_role_n_cat_table,"tr",'{"class":"upd_role_n_cat_parent_cont_style"}', "");
$upd_name_tbl_td = insertElement($upd_role_n_cat_tbl_name_tr,"td",'{"class":""}', "");
$upd_name_tbl_spans = insertElement($upd_name_tbl_td,"span",'{"class":""}', "Update Name");
$upd_name_txtbx_tbl_td = insertElement($upd_role_n_cat_tbl_name_tr,"td",'{"class":""}', "");
$upd_name_tbl_txtbx = insertElement($upd_name_txtbx_tbl_td,"input",'{"id":"name_textbox","class":"name_textbox_style", "type":"text"}', "");

$upd_role_n_cat_tbl_desc_tr = insertElement($upd_role_n_cat_table,"tr",'{"class":"upd_role_n_cat_parent_cont_style"}', "");
$upd_desc_tbl_td = insertElement($upd_role_n_cat_tbl_desc_tr,"td",'{"class":""}', "");
$upd_desc_tbl_spans = insertElement($upd_desc_tbl_td,"span",'{"class":""}', "Update Description");
$upd_desc_txtarea_tbl_td = insertElement($upd_role_n_cat_tbl_desc_tr,"td",'{"class":""}', "");
$upd_desc_tbl_txtarea = insertElement($upd_desc_txtarea_tbl_td,"textarea",'{"id":"description_textbox","class":"description_textbox_style", "maxlength":"20"}', "");


$update_parent_cont = insertElement($upd_role_n_cat_parent_cont,"div",'{"id":"update_parent_cont","class":"update_parent_cont_style"}', ""); //update button div 
$update_cont = insertElement($update_parent_cont,"div",'{"id":"update_cont","class":"update_cont_style"}', "Update");
//====================== update role/ category dialog ends======================
///////////////////////////////////////////////////////////////
////////END OF DIALOGUES
///////////////////////////////////////////////////////////////

echo $doc->saveHTML();

$logfile->logfile_close();
?>


<?php

?>




