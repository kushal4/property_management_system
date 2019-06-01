<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$SENSESSION->delete("unit_type_id");
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

//include 'lib/php-lib/eecee_constants.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'reg_func.php';
//include 'sec.php';
$session_val= is_session_valid();
if($session_val==0){
}
else{
    header("Location: eecee_login.php");
}

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");
//clear map arrays from session

include $sense_common_php_lib_path.'master_layout.php';

addIcon($doc_head, "/favicon.ico");

// CSS Files :: START
addStyleSheet($doc_head, $eecee_styles_path."home.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."attrib_tbl.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."vert_tab.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."style_test03.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."unit_type_feature_tbl.css?".time());

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

addScriptPath($doc_head, "prop_def_unit_type.js?".time());
//addScriptPath($doc_head, "../lib/js-lib/vert_tab.js?".time());

// JS Files :: END

///////////////////////////////////////////////////////////////
////////MAIN LAYOUT CONTENT: START
///////////////////////////////////////////////////////////////

$unit_type_id = $SENSESSION->get_val("unit_type_id");
if($unit_type_id != null)

{
    $logfile->logfile_writeline("unit_type_id session key exists"); 
}else{
    $logfile->logfile_writeline("unit_type_id session key does not exist"); 
}

//***************************** UNIT TYPE LIST :: START **************************


//***************************** UNIT TYPE LIST :: END **************************

///////////////////////////////////////////////////////////////
////////START OF DIALOGUES
///////////////////////////////////////////////////////////////
$dialog_parent = $doc_body;

$add_feat_dialog = insertPanel($dialog_parent, '{"id":"add_feat_dialog", "class":"add_feat_dialog_style", "title":" "}',"");
$add_feat_dialog_cont = insertElement($add_feat_dialog,"div",'{"class":"add_feat_dialog_cont_style"}', "");
addAttribToElement($add_feat_dialog_cont, '{"id":"add_feat_dialog_cont"}');
$feat_name_cont = insertElement($add_feat_dialog,"div",'{"id":"feat_name_cont","class":"feat_name_cont_style"}', "");
$feat_name_span = insertElement($feat_name_cont,"span",'{"id":"feat_name_span","class":"feat_name_span_style"}', "Features::");

$feat_dropdown_cont = insertElement($add_feat_dialog,"div",'{"id":"feat_dropdown_cont","class":"feat_dropdown_cont_style"}', "");
$feat_dropdown = insertElement($feat_dropdown_cont,"select",'{"id":"feat_dropdown","class":"feat_dropdown_style"}', "");

$add_feat_btn_cont = insertElement($add_feat_dialog,"div",'{"id":"add_feat_btn_cont","class":"add_feat_btn_cont_style"}', "");
$add_feat_btn_span = insertElement($add_feat_btn_cont,"span",'{"id":"add_feat_btn_span","class":"add_feat_btn_span_style"}', "Add This Feature");


//feature delete dialog :: START 
$feat_delete_dialog = insertPanel($dialog_parent, '{"id":"feat_delete_dialog", "class":"feat_delete_dialog_style", "title":" "}',"");
$feat_delete_dialog_cont = insertElement($feat_delete_dialog,"div",'{"class":"feat_delete_dialog_cont_style"}', "");
addAttribToElement($feat_delete_dialog_cont, '{"id":"feat_delete_dialog_cont"}');

$feat_delete_question_cont = insertElement($feat_delete_dialog_cont,"div",'{"id":"feat_delete_question_cont","class":"feat_delete_question_cont_style"}', "");
$feat_delete_question_span = insertElement($feat_delete_question_cont,"span",'{"id":"feat_delete_question_span","class":"feat_delete_question_span_style"}', "Do you want to delete this feature?");

$feat_delete_yes_btn_cont = insertElement($feat_delete_dialog_cont,"div",'{"id":"feat_delete_yes_btn_cont","class":"feat_delete_yes_btn_cont_style"}', "");
$feat_delete_yes_btn_span = insertElement($feat_delete_yes_btn_cont,"span",'{"id":"feat_delete_yes_btn_span","class":"feat_delete_yes_btn_span_style"}', "Yes");

$feat_delete_no_btn_cont = insertElement($feat_delete_dialog_cont,"div",'{"id":"feat_delete_no_btn_cont","class":"feat_delete_no_btn_cont_style"}', "");
$feat_delete_no_btn_span = insertElement($feat_delete_no_btn_cont,"span",'{"id":"feat_delete_no_btn_span","class":"feat_delete_no_btn_span_style"}', "No");
//feature delete dialog :: END 



//unit type delete dialog :: START 
$unit_type_delete_dialog = insertPanel($dialog_parent, '{"id":"unit_type_delete_dialog", "class":"unit_type_delete_dialog_style", "title":" "}',"");
$unit_type_delete_dialog_cont = insertElement($unit_type_delete_dialog,"div",'{"class":"unit_type_delete_dialog_cont_style"}', "");
addAttribToElement($unit_type_delete_dialog_cont, '{"id":"unit_type_delete_dialog_cont"}');

$unit_type_delete_question_cont = insertElement($unit_type_delete_dialog_cont,"div",'{"id":"unit_type_delete_question_cont","class":"unit_type_delete_question_cont_style"}', "");
$unit_type_delete_question_span = insertElement($unit_type_delete_question_cont,"span",'{"id":"unit_type_delete_question_span","class":"unit_type_delete_question_span_style"}', "Do you want to delete this Unit Type?");

$unit_type_delete_yes_btn_cont = insertElement($unit_type_delete_dialog_cont,"div",'{"id":"unit_type_delete_yes_btn_cont","class":"unit_type_delete_yes_btn_cont_style"}', "");
$unit_type_delete_yes_btn_span = insertElement($unit_type_delete_yes_btn_cont,"span",'{"id":"unit_type_delete_yes_btn_span","class":"unit_type_delete_yes_btn_span_style"}', "Yes");

$unit_type_delete_no_btn_cont = insertElement($unit_type_delete_dialog_cont,"div",'{"id":"unit_type_delete_no_btn_cont","class":"unit_type_delete_no_btn_cont_style"}', "");
$unit_type_delete_no_btn_span = insertElement($unit_type_delete_no_btn_cont,"span",'{"id":"unit_type_delete_no_btn_span","class":"unit_type_delete_no_btn_span_style"}', "No");
//unit type delete dialog :: END 


//unit type update dialog :: START 
$unit_type_update_dialog = insertPanel($dialog_parent, '{"id":"unit_type_update_dialog", "class":"unit_type_update_dialog_style", "title":" "}',"");
$unit_type_update_dialog_cont = insertElement($unit_type_update_dialog,"div",'{"class":"unit_type_update_dialog_cont_style"}', "");
addAttribToElement($unit_type_update_dialog_cont, '{"id":"unit_type_update_dialog_cont"}');

$unit_type_update_question_cont = insertElement($unit_type_update_dialog_cont,"div",'{"id":"unit_type_update_question_cont","class":"unit_type_update_question_cont_style"}', "");
$unit_type_update_question_span = insertElement($unit_type_update_question_cont,"span",'{"id":"unit_type_update_question_span","class":"unit_type_update_question_span_style"}', "Do you want to update this Unit Type?");

$unit_type_update_yes_btn_cont = insertElement($unit_type_update_dialog_cont,"div",'{"id":"unit_type_update_yes_btn_cont","class":"unit_type_update_yes_btn_cont_style"}', "");
$unit_type_update_yes_btn_span = insertElement($unit_type_update_yes_btn_cont,"span",'{"id":"unit_type_update_yes_btn_span","class":"unit_type_update_yes_btn_span_style"}', "Yes");

$unit_type_update_no_btn_cont = insertElement($unit_type_update_dialog_cont,"div",'{"id":"unit_type_update_no_btn_cont","class":"unit_type_update_no_btn_cont_style"}', "");
$unit_type_update_no_btn_span = insertElement($unit_type_update_no_btn_cont,"span",'{"id":"unit_type_update_no_btn_span","class":"unit_type_update_no_btn_span_style"}', "No");
//unit type update dialog :: END 

// add attribute dialog :: START 

$add_attrib_dialog = insertPanel($dialog_parent, '{"id":"add_attrib_dialog", "class":"add_attrib_dialog_style", "title":" "}',"");
$add_attrib_dia_cont = insertPanel($add_attrib_dialog, '{"id":"add_attrib_dia_cont", "class":"add_attrib_dia_cont_style", "title":" "}',"");

/*
$add_attrib_dialog_cont = insertElement($add_attrib_dialog,"div",'{"class":"add_attrib_dialog_cont_style"}', "");
addAttribToElement($add_attrib_dialog_cont, '{"id":"add_attrib_dialog_cont"}');

$attrb_name_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_name_cont","class":"attrb_name_cont_style"}', "");
$attrb_name_span = insertElement($attrb_name_cont,"span",'{"id":"attrb_name_span","class":"attrb_name_span_style"}', "Attributes::");

$attrb_dropdown_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_dropdown_cont","class":"attrb_dropdown_cont_style"}', "");
$attrb_dropdown = insertElement($attrb_dropdown_cont,"select",'{"id":"attrb_dropdown","class":"attrb_dropdown_style"}', "");

$add_attrb_btn_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"add_attrb_btn_cont","class":"add_attrb_btn_cont_style"}', "");
$add_feat_btn_span = insertElement($add_attrb_btn_cont,"span",'{"id":"add_feat_btn_span","class":"add_feat_btn_span_style"}', "Add This Attribute");
*/
// add attribute dialog :: END 

///////////////////////////////////////////////////////////////
////////END OF DIALOGUES
///////////////////////////////////////////////////////////////

echo $doc->saveHTML();

$logfile->logfile_close();
?>
