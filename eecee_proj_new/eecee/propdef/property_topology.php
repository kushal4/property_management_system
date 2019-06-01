<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
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


include $sense_common_php_lib_path.'master_layout.php';

addIcon($doc_head, "/favicon.ico");

// CSS Files :: START
addStyleSheet($doc_head, $eecee_styles_path."home.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."attrib_tbl.css?".time());
addStyleSheet($doc_head, $eecee_ext_styles_path."jquery-ui.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."themes/default/style.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."/themes/default-dark/style.css");
addStyleSheet($doc_head, $eecee_styles_path."vert_tab.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."style_test03.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."unit_type_feature_tbl.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."prop_topo_acc.css?".time());
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
addScriptPath($doc_head, "prop_def.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."accordion.js?".time());


//addScriptPath($doc_head, $eecee_js_lib_path."common_js_file.js?".time());

// JS Files :: END

//Insert a tabs control
$maintabs = new jqueryui_tabs_widget("tabs");
$maintabs->setParent($layout_main_container);

$maintabs->insertTab("tabs_1", "Something");
$maintabs->insertTab("tabs_1", "Something 2");
$maintabs->addTabClass("tabs_1", "main_anchor");
$maintabs->addTabContainerClass("tabs_1", "main_tabs");

$maintabs->addViewtoTabContainer("tabs_1", "network_main_page");

$netlist_view = $maintabs->addViewtoTabContainer("tabs_1", "set_up_prop");

$netlist_view_global_heading = insertPanel($netlist_view,"","");

$netlist_view_accordion = new jqueryui_accordion_widget("global_accordion");
$netlist_view_accordion->addClass("genereic_accordion");
$netlist_view_accordion->addClass("prop_topo_accordion");
$netlist_view_accordion->setParent($netlist_view);

/*BEGINNING of set-up property*/
$netlist_view_accordion->insertTab("netlist","Property Details");
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

insertPanel($nva_nl_nlv_ps1, '{"id":"setup_prop_btn_cont"}',"" );


//addAttribToElement($user_prop_container, '{"id":"generic_row_cls0"}');
$prop_def_tree_pg_sec = insertPageSection  ($layout_main_container, '', '{"class":"prop_topo_page_section"}');


//$excel_button = insertElement($excel_button_cont,"div",'{"class":"excel_button_style"}'," ");  

$excel_container = insertElement($prop_def_tree_pg_sec,"div",'{"class":"excel_container_style"}'," ");  
$excel_container_image = insertElement($prop_def_tree_pg_sec,"span",'{"class":"excel_container_image_style" , "id":"excel_container_image"}',"excel");  
$demo_prop_tree_container = insertElement($prop_def_tree_pg_sec,"div",'{"class":"user_prop_container"}'," ");  
addAttribToElement($demo_prop_tree_container, '{"id":"prop_topo_tree"}');

///////////////////////////////////////////////////////////////
////////START OF DIALOGUES
///////////////////////////////////////////////////////////////
$dialog_parent = $doc_body;


$create_mul_prop_dialog = insertPanel($dialog_parent, '{"id":"create_mul_prop", "class":"create_mul_prop_style", "title":"Create Multiple "}',"");
insertElement($create_mul_prop_dialog,"span",'{"class":"heading_styles"}', "Create Unit or Group");

$excel_dialog = insertPanel($dialog_parent, '{"id":"excel_dialog", "class":"excel_dialog_style", "title":"Flat Details "}',"");
insertElement($excel_dialog,"span",'{"class":"heading_styles"}', "");
$flat_table_page_section = insertPageSection  ($excel_dialog, '', '{"class":"page-section"}');
$flat_table_page_section_heading_cont = $flat_table_page_section->firstChild;

$heading_left_child_cont = insertElement($flat_table_page_section_heading_cont,"div",'{"class":"heading_left_child_cont_style"}', "");
$heading_mid_child_cont = insertElement($flat_table_page_section_heading_cont,"div",'{"class":"heading_mid_child_cont_style"}', "");
$heading_right_child_cont = insertElement($flat_table_page_section_heading_cont,"div",'{"id":"heading_right_child_cont", "class":"heading_right_child_cont_style", "":""}', "");


///////// user details dialog starts ////////////

$user_dets_dialog = insertPanel($dialog_parent, '{"id":"user_dets_dialog", "class":"user_dets_dialog_style", "title":"User Details", "data-op": "adduser"}',"");
$user_dets_parent_div = insertElement($user_dets_dialog,"div",'{"class":"user_dets_parent_div_style", "data-op": "adduser"}', "");

unit_dialog($user_dets_parent_div);

//$flat_table_page_section_heading_cont->appendChild($ps_node);
//////////// user details dialog ends /////////////

///////// unit details dialog starts ////////////
$unit_dets_dialog = insertPanel($dialog_parent, '{"id":"unit_dets_dialog", "class":"unit_dets_dialog_style"}',"");
$unit_dets_dia_cont = insertElement($unit_dets_dialog,"div",'{"id":"unit_dets_dia_cont" ,"class":"unit_dets_dia_cont_style"}', "");
/*
$unit_dets_maintabs = new jqueryui_tabs_widget("ud_tabs");
$unit_dets_maintabs->setParent($unit_dets_dialog);
$unit_dets_maintabs->insertTab("ud_tabs_1", "Unit Details");
$unit_dets_maintabs->addTabClass("ud_tabs_1", "main_anchor");
$unit_dets_maintabs->addTabContainerClass("ud_tabs_1", "main_tabs");

$unit_dets_maintabs->addViewtoTabContainer("ud_tabs_1", "unit_details_view");

$unit_dets_view = $unit_dets_maintabs->addViewtoTabContainer("ud_tabs_1", "set_up_prop");

$unit_dets_view_panel = insertPanel($unit_dets_view,"","");
addAttribToElement($unit_dets_view_panel, '{"class":"test_class"}');
$unit_dets_view_panel_heading_cont = insertElement($unit_dets_view_panel,"div",'{"class":"unit_dets_view_panel_heading_cont_style"}', "");
$unit_dets_view_panel_heading_span = insertElement($unit_dets_view_panel_heading_cont,"span",'{"id": "unit_dets_view_panel_heading_span", "class":"unit_dets_view_panel_heading_span_style"}', "unit name");
$unit_dets_tbl_cont = insertElement($unit_dets_view_panel,"div",'{"id": "unit_dets_tbl_cont", "class":"unit_dets_tbl_cont_style"}', "");
*/
//$unit_dets_maintabs->insertTab("ud_tabs_2", "Unit Details 2");
///////// unit details dialog ends ////////////


//$excel_button_cont = insertElement($flat_table_page_section,"div",'{"id":"excel_button_cont", "class":"excel_button_cont_style", "":""}'," ");  
insertPanel($flat_table_page_section, '{"id":"flat_table"}',"");

$radio_div = insertElement($create_mul_prop_dialog,"div",'{"class":"radio_div_style"}', "");
$rad_pub = insertElement($radio_div,"input",'{"id":"type_unit", "class":"", "type":"radio", "name":"prop_typ_slctr", "value":"1", "checked":"checked"}', "");
$rad_pub_label = insertElement($radio_div,"label",'{"for":"type_unit", "class":""}', "unit");
$rad_glo = insertElement($radio_div,"input",'{"id":"type_group", "class":"", "type":"radio", "name":"prop_typ_slctr", "value":"0", "":""}', "");
$rad_glo_label = insertElement($radio_div,"label",'{"for":"type_group", "class":""}', "group");

$prop_num_cont = insertElement($create_mul_prop_dialog,"div",'{"class":"prop_num_cont_style"}', "");
$prop_num_span = insertElement($prop_num_cont,"span",'{"class":"prop_num_span_style"}', "How many?");
$prop_num_textbox_cont = insertElement($prop_num_cont,"div",'{"class":"prop_num_textbox_cont_style"}', "");
$prop_num_textbox = insertElement($prop_num_textbox_cont,"input",'{"id":"prop_num_textbox", "class":"prop_num_textbox_style", "type":"text", "":""}', "");

$name_format_cont = insertElement($create_mul_prop_dialog,"div",'{"class":"name_format_cont_style"}', "");
insertElement($name_format_cont,"span",'{"class":"heading_styles"}', "Naming format");


$name_format_dropdown__parent_cont = insertElement($create_mul_prop_dialog,"div",'{"id":"name_format_dropdown__parent_cont","class":"name_format_dropdown__parent_cont_style"}',"");

/*1st dropdown div starts */
$name_format_dropdown_cont1 = insertElement($name_format_dropdown__parent_cont,"div",'{"id":"name_format_dropdown_cont","class":"name_format_dropdown_cont_style"}',"");
$name_format_dropdown01 = insertElement($name_format_dropdown_cont1,"select",'{"id":"name_format_dropdown_1", "class":"name_format_dropdown_style"}', "");
$name_format_option01 = insertElement($name_format_dropdown01,"option",'{"id":"", "class":""}', "");
$name_format_option02 = insertElement($name_format_dropdown01,"option",'{"id":"", "class":""}', "fixed");
$name_format_option03 = insertElement($name_format_dropdown01,"option",'{"id":"", "class":""}', "alphabetical order");
$name_format_option04 = insertElement($name_format_dropdown01,"option",'{"id":"", "class":""}', "numeric order");
$first_dd_cont = insertElement($name_format_dropdown_cont1,"div",'{"id":"first_dd_cont","class":"first_dd_cont_style", "class" : "generic_select_cont"}',"");
$first_dd_sel_cont = insertElement($first_dd_cont,"div",'{"id":"first_dd_sel_cont","class":"first_dd_sel_alp_cont_style"}',"");
$first_dd_sel_span_cont = insertElement($first_dd_sel_cont,"div",'{"id":"first_dd_sel_span_cont","class":"first_dd_sel_span_cont_style"}'," ");
$first_dd_sel_span = insertElement($first_dd_sel_span_cont,"span",'{"id":"first_dd_sel_span","class":"first_dd_sel_alp_span_style"}',"Start alphabet");
$first_dd_sel_textbox = insertElement($first_dd_sel_cont,"input",'{"id":"first_dd_sel_textbox","class":"first_dd_sel_alp_textbox_style","class":"naming_format_generic_input"}',"");

$error_span_dd1_cont = insertElement($first_dd_cont,"div",'{"id":"error_span_dd1_cont","class":"generic_err_style"}',"");
$error_span_dd1_span = insertElement($error_span_dd1_cont,"span",'{"id":"error_span_dd1_span", "class":"ajax_err_hide"}', "abcderfjkl");
/*1st dropdown div ends */


/*2nd dropdown div starts */
$name_format_dropdown_cont2 = insertElement($name_format_dropdown__parent_cont,"div",'{"id":"name_format_dropdown_cont","class":"name_format_dropdown_cont_style"}',"");
$name_format_dropdown02 = insertElement($name_format_dropdown_cont2,"select",'{"id":"name_format_dropdown_2", "class":"name_format_dropdown_style"}', "");
$name_format_option01 = insertElement($name_format_dropdown02,"option",'{"id":"", "class":""}', "");
$name_format_option02 = insertElement($name_format_dropdown02,"option",'{"id":"", "class":""}', "fixed");
$name_format_option03 = insertElement($name_format_dropdown02,"option",'{"id":"", "class":""}', "alphabetical order");
$name_format_option04 = insertElement($name_format_dropdown02,"option",'{"id":"", "class":""}', "numeric order");
$second_dd_cont = insertElement($name_format_dropdown_cont2,"div",'{"id":"second_dd_cont","class":"second_dd_cont_style", "class" : "generic_select_cont"}',"");
$second_dd_sel_cont = insertElement($second_dd_cont,"div",'{"id":"second_dd_sel_cont","class":"second_dd_sel_alp_cont_style"}',"");
$second_dd_sel_span_cont = insertElement($second_dd_sel_cont,"div",'{"id":"second_dd_sel_span_cont","class":"second_dd_sel_span_cont_style"}'," ");
$second_dd_sel_span = insertElement($second_dd_sel_span_cont,"span",'{"id":"second_dd_sel_span","class":"second_dd_sel_alp_span_style"}',"Start alphabet");
$second_dd_sel_textbox = insertElement($second_dd_sel_cont,"input",'{"id":"second_dd_sel_textbox","class":"second_dd_sel_alp_textbox_textbox_style", "class":"naming_format_generic_input"}',"");

$error_span_dd2_cont = insertElement($second_dd_cont,"div",'{"id":"error_span_dd2_cont","class":"generic_err_style"}',"");
$error_span_dd2_span = insertElement($error_span_dd2_cont,"span",'{"id":"error_span_dd2_span", "class":"ajax_err_hide"}', "abcderfjkl");
/*2nd dropdown div ends */

/*3th dropdown div starts */
$name_format_dropdown_cont3 = insertElement($name_format_dropdown__parent_cont,"div",'{"id":"name_format_dropdown_cont","class":"name_format_dropdown_cont_style"}',"");
$name_format_dropdown03 = insertElement($name_format_dropdown_cont3,"select",'{"id":"name_format_dropdown_3", "class":"name_format_dropdown_style"}', "");
$name_format_option01 = insertElement($name_format_dropdown03,"option",'{"id":"", "class":""}', "");
$name_format_option02 = insertElement($name_format_dropdown03,"option",'{"id":"", "class":""}', "fixed");
$name_format_option03 = insertElement($name_format_dropdown03,"option",'{"id":"", "class":""}', "alphabetical order");
$name_format_option04 = insertElement($name_format_dropdown03,"option",'{"id":"", "class":""}', "numeric order");
$third_dd_cont = insertElement($name_format_dropdown_cont3,"div",'{"id":"third_dd_cont","class":"third_dd_cont_style", "class" : "generic_select_cont"}',"");

$third_dd_sel_cont = insertElement($third_dd_cont,"div",'{"id":"third_dd_sel_cont","class":"third_dd_sel_alp_cont_style"}',"");
$third_dd_sel_span_cont = insertElement($third_dd_sel_cont,"div",'{"id":"third_dd_sel_span_cont","class":"third_dd_sel_span_cont_style"}'," ");
$third_dd_sel_alp_span = insertElement($third_dd_sel_span_cont,"span",'{"id":"third_dd_sel_span","class":"third_dd_sel_alp_span_style"}',"Start alphabet");
$third_dd_sel_alp_textbox = insertElement($third_dd_sel_cont,"input",'{"id":"third_dd_sel_textbox","class":"third_dd_sel_alp_textbox_style", "class":"naming_format_generic_input"}',"");

$error_span_dd3_cont = insertElement($third_dd_cont,"div",'{"id":"error_span_dd3_cont","class":"generic_err_style"}',"");
$error_span_dd3_span = insertElement($error_span_dd3_cont,"span",'{"id":"error_span_dd3_span", "class":"ajax_err_hide"}', "abcderfjkl");
/*3th dropdown div ends */

/*4th dropdown div starts */
$name_format_dropdown_cont4 = insertElement($name_format_dropdown__parent_cont,"div",'{"id":"name_format_dropdown_cont","class":"name_format_dropdown_cont_style"}',"");
$name_format_dropdown04 = insertElement($name_format_dropdown_cont4,"select",'{"id":"name_format_dropdown_4", "class":"name_format_dropdown_style"}', "");
$name_format_option01 = insertElement($name_format_dropdown04,"option",'{"id":"", "class":""}', "");
$name_format_option02 = insertElement($name_format_dropdown04,"option",'{"id":"", "class":""}', "fixed");
$name_format_option03 = insertElement($name_format_dropdown04,"option",'{"id":"", "class":""}', "alphabetical order");
$name_format_option04 = insertElement($name_format_dropdown04,"option",'{"id":"", "class":""}', "numeric order");
$fourth_dd_cont = insertElement($name_format_dropdown_cont4,"div",'{"id":"fourth_dd_cont","class":"fourth_dd_cont_style", "class" : "generic_select_cont"}',"");

$fourth_dd_sel_cont = insertElement($fourth_dd_cont,"div",'{"id":"fourth_dd_sel_cont","class":"fourth_dd_sel_alp_cont_style"}',"");
$fourth_dd_sel_span_cont = insertElement($fourth_dd_sel_cont,"div",'{"id":"fourth_dd_sel_span_cont","class":"fourth_dd_sel_span_cont_style"}'," ");
$fourth_dd_sel_span = insertElement($fourth_dd_sel_span_cont,"span",'{"id":"fourth_dd_sel_span","class":"fourth_dd_sel_alp_span_style"}',"Start alphabet");
$fourth_dd_sel_textbox = insertElement($fourth_dd_sel_cont,"input",'{"id":"fourth_dd_sel_textbox","class":"fourth_dd_sel_alp_textbox_style", "class":"naming_format_generic_input"}',"");

//$fourth_dd_sel_let_cont = insertElement($fourth_dd_cont,"div",'{"id":"fourth_dd_sel_let_cont","class":"fourth_dd_sel_let_cont_style"}',"");
//$fourth_dd_sel_let_span = insertElement($fourth_dd_sel_let_cont,"span",'{"id":"fourth_dd_sel_let_span","class":"fourth_dd_sel_let_span_style"}',"Start number");
//$fourth_dd_sel_let_textbox = insertElement($fourth_dd_sel_let_cont,"input",'{"id":"fourth_dd_sel_let_textbox","class":"fourth_dd_sel_let_textbox_style", "class":"naming_format_generic_input"}',"");

//$fourth_dd_sel_fixed_cont = insertElement($fourth_dd_cont,"div",'{"id":"fourth_dd_sel_fixed_cont","class":"fourth_dd_sel_fixed_cont_style"}',"");
//$fourth_dd_sel_fixed_span = insertElement($fourth_dd_sel_fixed_cont,"span",'{"id":"fourth_dd_sel_fixed_span","class":"fourth_dd_sel_fixed_span_style"}',"Start number");
//$fourth_dd_sel_fixed_textbox = insertElement($fourth_dd_sel_fixed_cont,"input",'{"id":"fourth_dd_sel_fixed_textbox","class":"fourth_dd_sel_fixed_textbox_style","class":"naming_format_generic_input"}',"");
$error_span_dd4_cont = insertElement($fourth_dd_cont,"div",'{"id":"error_span_dd4_cont","class":"generic_err_style"}',"");
$error_span_dd4_span = insertElement($error_span_dd4_cont,"span",'{"id":"error_span_dd4_span", "class":"ajax_err_hide"}', "abcderfjkl");
/*4th dropdown div ends */

//$create_mul_prop_dialog = insertPanel($dialog_parent, '{"id":"create_mul_prop", "class":"create_mul_prop_style", "title":"Create Multiple "}',"");

$error_span_mul_prop_cont = insertElement($create_mul_prop_dialog,"div",'{"id":"error_span_mul_prop_cont","class":"error_span_mul_prop_cont_style"}',"");
$error_span_mul_prop = insertElement($error_span_mul_prop_cont,"span",'{"id":"error_span_mul_prop", "class":"ajax_err_hide"}', "");
$create_mul_prop_btn_cont = insertElement($create_mul_prop_dialog,"div",'{"id":"create_mul_prop_btn_cont","class":"create_mul_prop_style"}',"");
///////////////////////////////////////////////////////////////
////////END OF DIALOGUES
///////////////////////////////////////////////////////////////

echo $doc->saveHTML();

$logfile->logfile_close();
?>



