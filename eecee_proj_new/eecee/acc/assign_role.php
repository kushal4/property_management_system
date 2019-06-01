
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
include $sense_common_php_lib_path.'reg_func.php';
include $sense_common_php_lib_path.'sec.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

$session_val= is_session_valid();
if($session_val==0){
}
else{
    header("Location: eecee_login.php");
}

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

addScriptPath($doc_head, $eecee_js_lib_path."accordion.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); 
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."common_js_file.js?".time());

addScriptPath($doc_head, "acc_man.js?".time());
addScriptPath($doc_head, "assign_role.js?".time());
// JS Files :: END

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $SENSESSION->get_val("user_id");
$prop_id = $SENSESSION->get_val("prop_id");

//echo "the user ID:: ".$user_id."</br>";
//echo "the prop ID:: ".$prop_id."</br>";

$role_assign_parent_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");
$assign_role_scope_accordion = new jqueryui_accordion_widget("assign_role_accordion");
$assign_role_scope_accordion->addClass("genereic_accordion");
$assign_role_scope_accordion->setParent($role_assign_parent_container);

// beginning of first fold
$assign_role_scope_accordion->insertTab("fold1","Select User");
//$div_underH3 = insertElement($assign_role_scope_accordion,"div",'{"id":"div_underH3", "class":"div_underH3_style"}'," ");
$assign_role_fold1_view = $assign_role_scope_accordion->addViewtoTabContainer("fold1","fold1_view");
$assign_role_fold1_ps1 = insertPageSection  ($assign_role_fold1_view, '', '{"class":"page-section"}');

$fold1_heading_parent_cont = insertElement($assign_role_fold1_ps1,"div",'{"id":"fold1_heading_parent_cont", "class":"fold1_heading_parent_cont_style"}'," ");
$fold1_dd_cont = insertElement($fold1_heading_parent_cont,"div",'{"id":"fold1_dd_cont", "class":"fold1_dd_cont_style"}'," ");
$fold1_dd = insertElement($fold1_dd_cont,"select",'{"id":"fold1_dd", "class":""}',"fold1_dd_style");
$fold1_blank_op = insertElement($fold1_dd,"option",'{"id":"fold1_blank_op", "class":"fold1_blank_op_style", "value":"0"}',"");
$fold1_res_op = insertElement($fold1_dd,"option",'{"id":"fold1_res_op", "class":"fold1_res_op_style", "value":"res"}',"Resident");
$fold1_non_res_opt = insertElement($fold1_dd,"option",'{"id":"fold1_non_res_opt", "class":"fold1_non_res_opt_style", "value":"nonres"}',"Non Resident");
$fold1_all_opt = insertElement($fold1_dd,"option",'{"id":"fold1_all_opt", "class":"fold1_all_opt_style", "value":"all"}',"All");

$fold1_searchbox_cont = insertElement($fold1_heading_parent_cont,"div",'{"id":"fold1_searchbox_cont", "class":"fold1_searchbox_cont_style"}'," ");
$fold1_searchbox = insertElement($fold1_searchbox_cont,"input",'{"id":"fold1_searchbox", "class":"fold1_searchbox-style", "type":"text", "placeholder":"Search"}',"");


$user_table_container = insertPanel($assign_role_fold1_ps1,'',"");
$user_table_container->setAttribute('id',"sel_prop_tbl_container");
addAttribToElement($user_table_container, '{"class":"sel_prop_tbl_container_styl"}');
//end of first fold

// beginning of second fold
$assign_role_scope_accordion->insertTab("fold2","User Details");

$assign_role_fold2_view = $assign_role_scope_accordion->addViewtoTabContainer("fold2","fold2_view");
$assign_role_fold2_ps2 = insertPageSection  ($assign_role_fold2_view, '', '{"class":"page-section"}');
//$tbl1_container = insertElement($assign_role_fold2_ps2,"div",'{"id": "tbl1_container","class":"tbl1_container_style"}'," ");  
//$tbl2_container = insertElement($assign_role_fold2_ps2,"div",'{"id": "tbl2_container","class":"tbl2_container_style"}'," ");

////********************* table 01 starts here *********************
$tbl1_container_panel = insertPanel($assign_role_fold2_ps2,'',"");
$tbl1_container_panel->setAttribute('id',"tbl1_container");
$tbl1_container_panel->setAttribute('class',"tbl1_container_style");

$user_details_table_01 = new sense_table([
    //'id'=>'user_details_table_01',
    'widgetStyle'=>'wdg_sty',
    'headingStyle'=>'heading_sty',
    'headingText' => 'Test Table Heading Text',
    'headingTextStyle' => 'heading-text-sty',
    'contentTableStyle' => 'generic_table',
    'contentStyle' => 'user_details_table_02_style'
]);
$the_table01 = $user_details_table_01->setParent($tbl1_container_panel);


$row_obj = $user_details_table_01->addRow();
$user_details_table_01->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $user_details_table_01->addCell("User", TRUE);
$user_details_table_01->setCurrCellID("ud_t1_cell_1");

$cell_obj = $user_details_table_01->addCell("Details", TRUE);
$user_details_table_01->setCurrCellID("ud_t1_cell_2");


$row_obj = $user_details_table_01->addRow();
$user_details_table_01->setRowID($row_obj, "row_1");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $user_details_table_01->addCell("User Name");
$cell_obj->setAttribute('class',"ud_tbl_left_col_style");
$user_details_table_01->setCurrCellID("cell_1_1");
//$user_details_table_01->setAttribute('class',"ud_tbl_left_col_style");
$cell_obj = $user_details_table_01->addCell("");
$cell_obj->setAttribute('class',"ud_tbl_right_col_style");
$user_details_table_01->setCurrCellID("cell_1_2");
//$user_details_table_01->setAttribute('class',"ud_tbl_right_col_style");
$user_name_input = $doc->createElement('input');
$user_name_input->setAttribute('disabled',"disabled");
$user_name_input->setAttribute('class',"ud_tbl_input_style");
$user_details_table_01->insertUserInputElementIntoCurrCell($user_name_input, "user_name", "", "", '{"disabled":"disabled"}' );

$row_obj = $user_details_table_01->addRow();
$user_details_table_01->setRowID($row_obj, "row_2");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $user_details_table_01->addCell("Email");
$cell_obj->setAttribute('class',"ud_tbl_left_col_style");
$user_details_table_01->setCurrCellID("cell_2_1");
//$user_details_table_01->setAttribute('class',"ud_tbl_left_col_style");
$cell_obj = $user_details_table_01->addCell("");
$cell_obj->setAttribute('class',"ud_tbl_right_col_style");
$user_details_table_01->setCurrCellID("cell_2_2");
//$user_details_table_01->setAttribute('class',"ud_tbl_right_col_style");
$user_name_input = $doc->createElement('input');
$user_name_input->setAttribute('disabled',"disabled");
$user_name_input->setAttribute('class',"ud_tbl_input_style");
$user_details_table_01->insertUserInputElementIntoCurrCell($user_name_input, "emailid", "", "", '{"disabled":"disabled"}' );

$row_obj = $user_details_table_01->addRow();
$user_details_table_01->setRowID($row_obj, "row_3");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $user_details_table_01->addCell("Phone Numbers");
$cell_obj->setAttribute('class',"ud_tbl_left_col_style");
$user_details_table_01->setCurrCellID("cell_3_1");
//$user_details_table_01->setAttribute('class',"ud_tbl_left_col_style");
$cell_obj = $user_details_table_01->addCell("");
$cell_obj->setAttribute('class',"ud_tbl_right_col_style");
$user_details_table_01->setCurrCellID("cell_3_2");
//$user_details_table_01->setAttribute('class',"ud_tbl_right_col_style");
$user_name_input = $doc->createElement('input');
$user_name_input->setAttribute('disabled',"disabled");
$user_name_input->setAttribute('class',"ud_tbl_input_style");
$user_details_table_01->insertUserInputElementIntoCurrCell($user_name_input, "phnos", "", "", '{"disabled":"disabled"}' );

$row_obj = $user_details_table_01->addRow();
$user_details_table_01->setRowID($row_obj, "row_4");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $user_details_table_01->addCell("Emergency Phone Numbers");
$cell_obj->setAttribute('class',"ud_tbl_left_col_style");
$user_details_table_01->setCurrCellID("cell_4_1");
//$user_details_table_01->setAttribute('class',"ud_tbl_left_col_style");
$cell_obj = $user_details_table_01->addCell("");
$cell_obj->setAttribute('class',"ud_tbl_right_col_style");
$user_details_table_01->setCurrCellID("cell_4_2");
//$user_details_table_01->setAttribute('class',"ud_tbl_right_col_style");
$user_name_input = $doc->createElement('input');
$user_name_input->setAttribute('disabled',"disabled");
$user_name_input->setAttribute('class',"ud_tbl_input_style");
$user_details_table_01->insertUserInputElementIntoCurrCell($user_name_input, "ephnos", "", "", '{"disabled":"disabled"}' );

$row_obj = $user_details_table_01->addRow();
$user_details_table_01->setRowID($row_obj, "row_5");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $user_details_table_01->addCell("Blood Group");
$cell_obj->setAttribute('class',"ud_tbl_left_col_style");
$user_details_table_01->setCurrCellID("cell_5_1");
//$user_details_table_01->setAttribute('class',"ud_tbl_left_col_style");
$cell_obj = $user_details_table_01->addCell("");
$cell_obj->setAttribute('class',"ud_tbl_right_col_style");
$user_details_table_01->setCurrCellID("cell_5_2");
//$user_details_table_01->setAttribute('class',"ud_tbl_right_col_style");
$user_name_input = $doc->createElement('input');
$user_name_input->setAttribute('disabled',"disabled");
$user_name_input->setAttribute('class',"ud_tbl_input_style");
$user_details_table_01->insertUserInputElementIntoCurrCell($user_name_input, "bld_grp", "", "", '{"disabled":"disabled"}' );
//********************* table 01 ends here *********************

//********************* table 02 starts here *********************
$tbl2_container_panel = insertPanel($assign_role_fold2_ps2,'',"");
$tbl2_container_panel->setAttribute('id',"tbl2_container");
$tbl2_container_panel->setAttribute('class',"tbl2_container_style");

$user_details_table_02 = new sense_table([
    //'id'=>'user_details_table_02',
    'widgetStyle'=>'wdg_sty',
    'headingStyle'=>'heading_sty',
    'headingText' => 'Test Table Heading Text',
    'headingTextStyle' => 'heading-text-sty',
    'contentTableStyle' => 'generic_table',
    'contentStyle' => 'table_wrapper',
    
]);
$the_table02 = $user_details_table_02->setParent($tbl2_container_panel);

$row_obj = $user_details_table_02->addRow();
$user_details_table_02->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $user_details_table_02->addCell("Unit", TRUE);
$user_details_table_02->setCurrCellID("und_t2_cell_1");

$cell_obj = $user_details_table_02->addCell("Details", TRUE);
$user_details_table_02->setCurrCellID("und_t2_cell_2");

$row_obj = $user_details_table_02->addRow();
$user_details_table_02->setRowID($row_obj, "row_1");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $user_details_table_02->addCell("flat Name");
$cell_obj->setAttribute('class',"und_tbl_left_col_style");
$user_details_table_02->setCurrCellID("cell_1_1");
//$user_details_table_02->setAttribute('class',"und_tbl_left_col_style");
$cell_obj = $user_details_table_02->addCell("");
$cell_obj->setAttribute('class',"und_tbl_right_col_style");
$user_details_table_02->setCurrCellID("cell_1_2");
//$user_details_table_02->setAttribute('class',"und_tbl_right_col_style");
$user_name_input = $doc->createElement('input');
$user_name_input->setAttribute('disabled',"disabled");
$user_name_input->setAttribute('class',"und_tbl_input_style");
$user_details_table_02->insertUserInputElementIntoCurrCell($user_name_input, "flat_name", "", "", '{"disabled":"disabled"}' );

$row_obj = $user_details_table_02->addRow();
$user_details_table_02->setRowID($row_obj, "row_2");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $user_details_table_02->addCell("Area");
$cell_obj->setAttribute('class',"und_tbl_left_col_style");
$user_details_table_02->setCurrCellID("cell_2_1");
//$user_details_table_02->setAttribute('class',"und_tbl_left_col_style");
$cell_obj = $user_details_table_02->addCell("");
$cell_obj->setAttribute('class',"und_tbl_right_col_style");
$user_details_table_02->setCurrCellID("cell_2_2");
//$user_details_table_02->setAttribute('class',"und_tbl_right_col_style");
$user_name_input = $doc->createElement('input');
$user_name_input->setAttribute('disabled',"disabled");
$user_name_input->setAttribute('class',"und_tbl_input_style");
$user_details_table_02->insertUserInputElementIntoCurrCell($user_name_input, "area", "", "", '{"disabled":"disabled"}' );
//********************* table 02 ends here *********************

//end of second fold

// beginning of third fold
$assign_role_scope_accordion->insertTab("fold3","User Roles");
$assign_role_fold3_view = $assign_role_scope_accordion->addViewtoTabContainer("fold3","fold3_view");
$assign_role_fold3_ps3 = insertPageSection  ($assign_role_fold3_view, '', '{"class":"page-section"}');
$user_role_tree_container = insertElement($assign_role_fold3_ps3,"div",'{"class":"user_prop_container"}'," ");  
addAttribToElement($user_role_tree_container, '{"id":"assign_role_tree"}');
$update_button_container = insertElement($assign_role_fold3_ps3,"div",'{"id":"update_button_container", "class": "update_button_container_style"}'," ");
$update_button = insertElement($update_button_container,"div",'{"id":"update_button", "class": "update_button_style"}',"Update");  
//end of third fold



/*
$featureTable = new sense_table([
    //'id'=>'test_tbl',
    'widgetStyle'=>'wdg_sty',
    'headingStyle'=>'heading_sty',
    'headingText' => 'Test Table Heading Text',
    'headingTextStyle' => 'heading-text-sty',
    'contentTableStyle' => 'generic_table',
    'contentStyle' => 'usr_table_wrapper'
    
]);
$featureTable->setParent($user_table_container);

$row_obj = $featureTable->addRow();
$featureTable->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');

$cell_obj = $featureTable->addCell("User Name", TRUE);
$featureTable->setCurrCellID("cell_1");
addAttribToElement($cell_obj, '{"class":"user_name_col_heading_style"}');

$cell_obj = $featureTable->addCell("Unit Name", TRUE);
$featureTable->setCurrCellID("cell_2");
addAttribToElement($cell_obj, '{"class":"unit_name_col_style"}');

$cell_obj = $featureTable->addCell("Capacity", TRUE);
$featureTable->setCurrCellID("cell_3");
addAttribToElement($cell_obj, '{"class":"capacity_col_style"}');

$sql_user_prop = "SELECT * FROM contexts WHERE prop_id = ?";
$user_prop_stmt = $conn->prepare($sql_user_prop);
$user_prop_stmt->bind_param("i", $prop_id);
$user_prop_stmt->execute();
$user_prop_result = $user_prop_stmt->get_result();
$user_prop_result_assoc = $user_prop_result->fetch_all();
$num_row = mysqli_num_rows($user_prop_result);

foreach ($user_prop_result_assoc as $value){ 
    $unit_id = $value["4"];
    $user_type = $value["2"];
    $userID = $value["1"];

    if($unit_id != NULL){
        $search_str="user_id=".$userID;
        $op_ret_fname_obj = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_FN", $userID, "user_id=".$userID);
        $op_ret_lname_obj = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_LN", $userID, "user_id=".$userID);
        $userName = $op_ret_fname_obj["val"]." ".$op_ret_lname_obj["val"];
        
        $sql_prop_topo = "SELECT * FROM prop_topo WHERE id = ? and prop_id = ?";
        $prop_topo_stmt = $conn->prepare($sql_prop_topo);
        $prop_topo_stmt->bind_param("ii",$unit_id, $prop_id);
        $prop_topo_stmt->execute();
        $prop_topo_result = $prop_topo_stmt->get_result();
        $user_prop_result_assoc = $prop_topo_result->fetch_assoc();
        $unit_name = $user_prop_result_assoc['node_name'];
        
        $row_obj = $featureTable->addRow();
        $featureTable->setRowID($row_obj, "row_".$unit_id);
        
        $secedUserID = sec_push_val_single_entry ("user_id_map", $userID); // user_id will be secced here

        $cell_obj = $featureTable->addCell($userName);
        $featureTable->setCurrCellID($secedUserID);
        addAttribToElement($cell_obj, '{"class":"user_name_col_style"}');
        addDataToElement($cell_obj, '{"name":"'.$userName.'"}');

        $cell_obj = $featureTable->addCell($unit_name);
        $featureTable->setCurrCellID("cell_2");
        
        if($user_type != NULL && $user_type == 1 ){
            $cell_obj = $featureTable->addCell("Owner");
            $featureTable->setCurrCellID("cell_3");
        }else if($user_type != NULL && $user_type == 2){
            $cell_obj = $featureTable->addCell("Co-Owner");
            $featureTable->setCurrCellID("cell_3");
        }else if($user_type != NULL && $user_type == 3){
            $cell_obj = $featureTable->addCell("Family Member");
            $featureTable->setCurrCellID("cell_3");
        }else if($user_type != NULL && $user_type ==42){
            $cell_obj = $featureTable->addCell("Tenant");
            $featureTable->setCurrCellID("cell_3");
        }
    }
    
}
*/
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

