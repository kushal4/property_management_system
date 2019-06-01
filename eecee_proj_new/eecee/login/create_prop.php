<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
//echo "opening dev.php";
$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";
require_once $sense_common_php_lib_path.'Log.php';
//include 'lib/php-lib/eecee_constants.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
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
/*

*/


//echo "before master_layout.php";
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

addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); // rr
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."accordion.js?".time());
//addScriptPath($doc_head, "../lib/js-lib/vert_tab.js?".time());
//addScriptPath($doc_head, "lib/js-lib/accordion.js?".time());



///////////////////////////////////////////////////////////////
////////MAIN LAYOUT CONTENT: START
///////////////////////////////////////////////////////////////
//Insert a tabs control
$maintabs = new jqueryui_tabs_widget("tabs");
$maintabs->setParent($layout_main_container);

///////////////////////////////////////////////////////////////
////////START OF NETWORK TAB
///////////////////////////////////////////////////////////////

$maintabs->insertTab("tabs_1", "Networks");
$maintabs->addTabClass("tabs_1", "main_anchor");
$maintabs->addTabContainerClass("tabs_1", "main_tabs");

///////////////////////////////////////////////////////////////
////////START OF NETWORK: LANDING VIEW
///////////////////////////////////////////////////////////////

$maintabs->addViewtoTabContainer("tabs_1", "network_main_page");
//$maintabs->insertDOMFromFileIntoTabView("network_main_page", "set_up_prop.php");

///////////////////////////////////////////////////////////////
////////END OF NETWORK: LANDING VIEW
///////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////
////////START OF NETWORK: GLOBAL SCOPE VIEW
///////////////////////////////////////////////////////////////

//This is how content of a view can be created programatically
$netlist_view = $maintabs->addViewtoTabContainer("tabs_1", "set_up_prop");

$netlist_view_global_heading = insertPanel($netlist_view,"","");
//insertElement($netlist_view_global_heading,"span",'{"class":"heading_key"}',"Scope: ");
//insertElement($netlist_view_global_heading,"span",'{"class":"heading_value"}',"Global ");
//insertPanel($netlist_view_global_heading,'{"class":"spacer"}',"");

$netlist_view_accordion = new jqueryui_accordion_widget("global_accordion");
$netlist_view_accordion->addClass("genereic_accordion");
$netlist_view_accordion->setParent($netlist_view);

/*BEGINNING of set-up property*/
$netlist_view_accordion->insertTab("netlist","Set-Up Property");
$nva_nl_nlv = $netlist_view_accordion->addViewtoTabContainer("netlist","netlist_view");
$nva_nl_nlv_ps1 = insertPageSection  ($nva_nl_nlv, '', '{"class":"page-section"}');
insertPanel($nva_nl_nlv_ps1, '{"id":"setup_prop"}',"");

insertPanel($nva_nl_nlv_ps1, '{"id":"", "class":"ajax_err_show"}',"" );

/*END of set-up property*/

$setup_prop_table_container = insertPanel($nva_nl_nlv_ps1,'',"");
$setup_prop_table_container->setAttribute('id',"setup_prop_tbl_container");
insertPanel($nva_nl_nlv_ps1, '{"id":"setup_prop_btn_cont"}',"" );

//$setup_prop_table = new sense_table("test_tbl");
$setup_prop_table = new sense_table([
                                //'id'=>'test_tbl',
                                'widgetStyle'=>'wdg_sty',
                                'headingStyle'=>'heading_sty',
                                'headingText' => 'Test Table Heading Text',
                                'headingTextStyle' => 'heading-text-sty',
                                'contentTableStyle' => 'generic_table',
                                'contentStyle' => 'table_wrapper'
                              ]);


//$setup_prop_table->setAttrib('{"class":"generic_table"}');

$the_table = $setup_prop_table->setParent($setup_prop_table_container);
//addAttrToNode ($the_table, "class","generic_table");
//addAttribToElement($setup_prop_table, '{"class":"generic_table"}');

$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("Property", TRUE);
$setup_prop_table->setCurrCellID("cell_1");

$cell_obj = $setup_prop_table->addCell("Details", TRUE);
$setup_prop_table->setCurrCellID("cell_2");

$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_1");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("Property Name");
$setup_prop_table->setCurrCellID("cell_1_1");
$cell_obj = $setup_prop_table->addCell("");
$setup_prop_table->setCurrCellID("cell_1_2");
$prop_name_input = $doc->createElement('input');
$setup_prop_table->insertUserInputElementIntoCurrCell($prop_name_input, "setup_name", "", "", '{"ajax":"1", "type":"string", "type_check":"1", "maxlen_check":"1", "maxlen":"8"}' );

//$prop_name_input->setAttribute('id','setup_name');
//$setup_prop_table->insertElementIntoCurrCell($prop_name_input);

$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_2");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("Property Address");
$setup_prop_table->setCurrCellID("cell_2_1");
$cell_obj = $setup_prop_table->addCell("");
$setup_prop_table->setCurrCellID("cell_3_2");

$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_3");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("Address Line 1");
addAttribToElement($cell_obj, '{"class":"text_align_sty"}');
$setup_prop_table->setCurrCellID("cell_3_1");
$cell_obj = $setup_prop_table->addCell("");
$setup_prop_table->setCurrCellID("cell_3_2");
$prop_add1_input = $doc->createElement('input');


$setup_prop_table->insertUserInputElementIntoCurrCell($prop_add1_input, "setup_add1", "", "", '{"ajax":"1", "type":"string", "type_check":"1", "maxlen_check":"1", "maxlen":"16"}' );

//$prop_add1_input->setAttribute('id','setup_add1');
//$setup_prop_table->insertElementIntoCurrCell($prop_add1_input);

$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_4");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("Address Line 2");
addAttribToElement($cell_obj, '{"class":"text_align_sty"}');
$setup_prop_table->setCurrCellID("cell_4_1");
$cell_obj = $setup_prop_table->addCell("");
$setup_prop_table->setCurrCellID("cell_4_2");
$prop_add2_input = $doc->createElement('input');
//$prop_add2_input->setAttribute('id','setup_add2');
//$setup_prop_table->insertElementIntoCurrCell($prop_add2_input);
$setup_prop_table->insertUserInputElementIntoCurrCell($prop_add2_input, "setup_add2", "", "", '{"ajax":"1", "type":"string", "type_check":"1", "maxlen_check":"1", "maxlen":"16"}' );


$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_5");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("Locality");
addAttribToElement($cell_obj, '{"class":"text_align_sty"}');
$setup_prop_table->setCurrCellID("cell_5_1");
$cell_obj = $setup_prop_table->addCell("");
$setup_prop_table->setCurrCellID("cell_5_2");
$prop_locality_input = $doc->createElement('input');
//$prop_locality_input->setAttribute('id','setup_locality');
//$setup_prop_table->insertElementIntoCurrCell($prop_locality_input);
$setup_prop_table->insertUserInputElementIntoCurrCell($prop_locality_input, "setup_locality", "", "", '{"ajax":"1", "type":"string", "type_check":"1", "maxlen_check":"1", "maxlen":"26"}' );

$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_6");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("City");
addAttribToElement($cell_obj, '{"class":"text_align_sty"}');
$setup_prop_table->setCurrCellID("cell_6_1");
$cell_obj = $setup_prop_table->addCell("");
$setup_prop_table->setCurrCellID("cell_6_2");
$prop_city_input = $doc->createElement('input');
//$prop_city_input->setAttribute('id','setup_city');
//$setup_prop_table->insertElementIntoCurrCell($prop_city_input);
$setup_prop_table->insertUserInputElementIntoCurrCell($prop_city_input, "setup_city", "", "", '{"ajax":"1", "type":"string", "type_check":"1", "maxlen_check":"1", "maxlen":"20"}' );

$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_7");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("State");
addAttribToElement($cell_obj, '{"class":"text_align_sty"}');
$setup_prop_table->setCurrCellID("cell_7_1");
$cell_obj = $setup_prop_table->addCell("");
$setup_prop_table->setCurrCellID("cell_7_2");
$prop_state_input = $doc->createElement('input');
//$prop_state_input->setAttribute('id','setup_state');
//$setup_prop_table->insertElementIntoCurrCell($prop_state_input);
$setup_prop_table->insertUserInputElementIntoCurrCell($prop_state_input, "setup_state", "", "", '{"ajax":"1", "type":"string", "type_check":"1", "maxlen_check":"1", "maxlen":"20"}' );

$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_8");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("Country");
addAttribToElement($cell_obj, '{"class":"text_align_sty"}');
$setup_prop_table->setCurrCellID("cell_8_1");
$cell_obj = $setup_prop_table->addCell("");
$setup_prop_table->setCurrCellID("cell_8_2");
$prop_country_input = $doc->createElement('input');
//$prop_country_input->setAttribute('id','setup_country');
//$setup_prop_table->insertElementIntoCurrCell($prop_country_input);
$setup_prop_table->insertUserInputElementIntoCurrCell($prop_country_input, "setup_country", "", "", '{"ajax":"1", "type":"string", "type_check":"1", "maxlen_check":"1", "maxlen":"16"}' );

$row_obj = $setup_prop_table->addRow();
$setup_prop_table->setRowID($row_obj, "row_9");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $setup_prop_table->addCell("Pincode");
addAttribToElement($cell_obj, '{"class":"text_align_sty"}');
$setup_prop_table->setCurrCellID("cell_9_1");
$cell_obj = $setup_prop_table->addCell("");
$setup_prop_table->setCurrCellID("cell_9_2");
$prop_pincode_input = $doc->createElement('input');
//$prop_pincode_input->setAttribute('id','setup_pincode');
//$setup_prop_table->insertElementIntoCurrCell($prop_pincode_input);
$setup_prop_table->insertUserInputElementIntoCurrCell($prop_pincode_input, "setup_pincode", "", "", '{"ajax":"1", "type":"integer", "type_check":"1", "maxlen_check":"1", "maxlen":"6"}' );

insertPanel($nva_nl_nlv_ps1, '{"id":"setup_prop_btn_cont"}',"" );

echo $doc->saveHTML();

$logfile->logfile_close();
?>



