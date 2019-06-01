<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
//echo phpinfo();
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
include 'lib/php-lib/eecee_sec_map.php';
include '../lib/php-lib/session_exp.php';
include '../lib/php-lib/dom_func.php';
require '../lib/php-lib/composite_control_classes.php';
include '../lib/php-lib/reg_func.php';
include '../lib/php-lib/sec.php';
$log_path = "Logs/eecee.log";
//include 'sec.php';
$session_val= is_session_valid();
if($session_val==0){
}
else{
    header("Location: eecee_login.php");
}

//user_id: ID of the user to whome the requested data belong
function create_user_details_table_row($owner_dets_table, $doc, $conn, String $permSigPriID, String $row_caption, $row_name, $insertInput=1, $inputTag, $log, $control_elem=NULL){
    $row_obj = $owner_dets_table->addRow();
    //$owner_dets_table->addControElement("USER_NAME");
    $owner_dets_table->setRowID($row_obj, "row_".$row_name);
    addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');

    $cell_obj = $owner_dets_table->addCell("");
    $owner_dets_table->setCurrCellID("cell_".$row_name."_1");
    addAttribToElement($cell_obj, '{"class":"generic_cell_1_style"}');

    $cell_obj = $owner_dets_table->addCell($row_caption);
    $owner_dets_table->setCurrCellID("cell_".$row_name."_2");

    $cell_obj = $owner_dets_table->addCell("");
    $owner_dets_table->setCurrCellID("cell_".$row_name."_3");
    addAttribToElement($cell_obj, '{"class":"generic_cell_3_style"}');

    $cell_obj = $owner_dets_table->addCell("");
    $owner_dets_table->setCurrCellID("cell_".$row_name."_4");
    addAttribToElement($cell_obj, '{"class":"generic_cell_4_style"}');
    
    if($insertInput == 1){
        insert_user_details_table_userInput($owner_dets_table, $conn, $doc, $inputTag, $permSigPriID, $log, $control_elem);
    } else {
        if ($control_elem!=NULL) {
            insert_user_details_table_userInput($owner_dets_table, $conn, $doc, NULL, $permSigPriID, $log, $control_elem);
        }
    }

    $cell_obj = $owner_dets_table->addCell("");
    $owner_dets_table->setCurrCellID("cell_".$row_name."_5");

    $cell_obj = $owner_dets_table->addCell("");
    $owner_dets_table->setCurrCellID("cell_".$row_name."_6");

    $cell_obj = $owner_dets_table->addCell("");
    $owner_dets_table->setCurrCellID("cell_".$row_name."_7");

    $cell_obj = $owner_dets_table->addCell("");
    $owner_dets_table->setCurrCellID("cell_".$row_name."_8");
    return $row_obj;
}


function insert_user_details_table_userInput($owner_dets_table, $conn, $doc, $elem_name=NULL, $permSigPriID, $log, $control_elem=NULL){
    $parent_cont = $doc->createElement('div');
    $owner_dets_table->insertUserInputElementIntoCurrCell($parent_cont, "", "", "", '{"ajax":"1", "type":"string", "type_check":"1", "maxlen_check":"1", "maxlen":"8"}' );
    $child1 = insertElement($parent_cont,"div",'{"class":"heading_left_child_cont_style"}', "");
    $child2 = insertElement($parent_cont,"div",'{"class":"heading_left_child_cont_style"}', "");
    $seced_permSigPriID="";
    if ($elem_name!=NULL) {
       $input_elem = createUserInputElement ($elem_name, $doc, $conn, $permSigPriID, $seced_permSigPriID, $log);
       $child1->appendChild($input_elem);
    }
    if ($control_elem!=NULL) {
        $child2_id = "edit-".$seced_permSigPriID;
        addAttribToElement($child2, '{"id":"'.$child2_id.'"}');
        //$ctrl_elem = $owner_dets_table->addControElement("USER_NAME");
        //$child2->appendChild($ctrl_elem);
        $child2->appendChild($control_elem);
    }
}







function populate_master_gender($doc, $el, $p = NULL){
    $opt_el = $doc->createElement("option");
    //$opt_el->textContent = "======Select Country=====";
    //$opt_el->nodeValue = "random_value";
    addAttribToElement($opt_el, '{"value":"undefined"}');
    $opt_el->textContent = "Select Gender";
    $el->appendChild($opt_el);
}

function populate_master_country($doc, $el, $p = NULL){
    $opt_el = $doc->createElement("option");
    //$opt_el->textContent = "======Select Country=====";
    //$opt_el->nodeValue = "random_value";
    addAttribToElement($opt_el, '{"value":"undefined"}');
    $opt_el->textContent = "Select Country";
    $el->appendChild($opt_el);
}

function populate_master_state($doc, $el, $p = NULL){
    $opt_el = $doc->createElement("option");
    //$opt_el->textContent = "======Select Country=====";
    //$opt_el->nodeValue = "random_value";
    addAttribToElement($opt_el, '{"value":"undefined"}');
    $opt_el->textContent = "Select State";
    $el->appendChild($opt_el);
}

function populate_master_city($doc, $el, $p = NULL){
    $opt_el = $doc->createElement("option");
    //$opt_el->textContent = "======Select Country=====";
    //$opt_el->nodeValue = "random_value";
    addAttribToElement($opt_el, '{"value":"undefined"}');
    $opt_el->textContent = "Select City";
    $el->appendChild($opt_el);
}

function populate_master_country_code($doc, $el, $p = NULL){
    $opt_el = $doc->createElement("option");
    //$opt_el->textContent = "======Select Country=====";
    //$opt_el->nodeValue = "random_value";
    addAttribToElement($opt_el, '{"value":"undefined"}');
    $opt_el->textContent = "Select Country Code";
    $el->appendChild($opt_el);  
}

function populate_master_blood_grp($doc, $el, $p = NULL){
    $opt_el = $doc->createElement("option");
    //$opt_el->textContent = "======Select Country=====";
    //$opt_el->nodeValue = "random_value";
    addAttribToElement($opt_el, '{"value":"undefined"}');
    $opt_el->textContent = "Select Blood Group";
    $el->appendChild($opt_el);
}


$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");
//clear map arrays from session

//$logfile = new \Sense\Log("Logs/tarantoo.log", __FILE__);
//$logfile->logfile_open("w");
//$fle_handle=fopen("/home/steffi/Steffi_usr/stef/tarantoo/Logs/tarantoo.log","w");
//$fle_handle=fopen("Logs/tarantoo.log","w");

//echo "before master_layout.php";
include "../lib/php-lib/master_layout.php";

addIcon($doc_head, "/favicon.ico");

addStyleSheet($doc_head, "themes/home.css?".time());
addStyleSheet($doc_head, "../ext-styles/jquery-ui.css");
addStyleSheet($doc_head, "../ext-styles/themes/default/style.css");
addStyleSheet($doc_head, "../ext-styles/themes/default-dark/style.css");
//addStyleSheet($doc_head, "../styles/vert_tab.css");
addStyleSheet($doc_head, "themes/vert_tab.css?".time());
//addStyleSheet($doc_head, "themes/style.css");
//addStyleSheet($doc_head, "themes/style_test01.css");
//addStyleSheet($doc_head, "themes/style_test02.css");
addStyleSheet($doc_head, "themes/style_test03.css?".time());

addScriptPath($doc_head, "../ext_lib/js-lib/jquery-3.2.1.js");
addScriptPath($doc_head, "../ext_lib/js-lib/jquery-ui.js");
addScriptPath($doc_head, "../ext_lib/js-lib/jstree.js");
//addScriptPath($doc_head, "../ext_lib/jquery-ui.js");
addScriptPath($doc_head, "../lib/js-lib/resize.js?".time());
addScriptPath($doc_head, "lib/tarantoo_glob_var.js?".time());
addScriptPath($doc_head, "../lib/js-lib/sense-lib.js?".time());
//addScriptPath($doc_head, "lib/tarantoo_net.js?".time());
//addScriptPath($doc_head, "lib/tarantoo_instance.js?".time());
//addScriptPath($doc_head, "lib/tarantoo_client.js?".time());
addScriptPath($doc_head, "lib/eecee.js?".time());
addScriptPath($doc_head, "lib/additional.js?".time());
addScriptPath($doc_head, "lib/js-lib/prop_def.js?".time());
addScriptPath($doc_head, "lib/js-lib/user_details.js?".time());
addScriptPath($doc_head, "../lib/js-lib/vert_tab.js?".time());
addScriptPath($doc_head, "lib/js-lib/accordion.js?".time());
//addScriptPath($doc_head, "lib/js-lib/dashboard.js?".time());
addScriptPath($doc_head, "../lib/js-lib/Chart.js?".time());
addScriptPath($doc_head, "../lib/js-lib/table_creator.js?".time());

///////////////////////////////////////////////////////////////
////////MAIN LAYOUT CONTENT: START
///////////////////////////////////////////////////////////////

$unit_id = $_SESSION["unit_id"];
$logged_user_id = $_SESSION["user_id"];

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

$check_user_prop = "SELECT * FROM contexts WHERE unit_id = ?";
$check_user_prop_temp = $conn->prepare($check_user_prop);
$check_user_prop_temp->bind_param("i",$unit_id);
$check_user_prop_temp->execute();
$check_user_prop_temp_result = $check_user_prop_temp->get_result();
$user_prop_temp_row = $check_user_prop_temp_result->fetch_assoc();
$user_type = $user_prop_temp_row['user_type'];


$check_properties = "SELECT * FROM properties WHERE id = ?";
$check_properties_temp = $conn->prepare($check_properties);
$check_properties_temp->bind_param("i",$prop_id);
$check_properties_temp->execute();
$check_properties_temp_result = $check_properties_temp->get_result();
$properties_row = $check_properties_temp_result->fetch_assoc();
$prop_name = $properties_row['setup_name'];




//echo "the property name is:: ".$prop_name."</br>";
$header_parent = insertElement($layout_main_container,"div",'{"id":"","class":"header_parent_style"}'," ");

$property_name_cont = insertElement($header_parent,"div",'{"id":"property_name_cont","class":"property_name_cont_style"}'," ");
$property_name = insertElement($property_name_cont,"span",'{"class":"property_name_style"}',"Property name: ".$prop_name);

$unit_name_cont = insertElement($header_parent,"div",'{"id":"unit_name_cont","class":"unit_name_cont_style"}'," ");
$unit_name = insertElement($unit_name_cont,"span",'{"class":"unit_name_style"}',"Unit name: ".$unit_name);


$maintabs = new jqueryui_tabs_widget("user_details_tabs");
$maintabs->setParent($layout_main_container);

$maintabs->insertTab("tabs_1", "Owners");
$maintabs->addTabClass("tabs_1", "main_anchor");
$maintabs->addTabContainerClass("tabs_1", "main_tabs");

$maintabs->insertTab("tabs_2", "Tenants");
$maintabs->addTabClass("tabs_2", "main_anchor");
$maintabs->addTabContainerClass("tabs_2", "main_tabs");

$owner_view = $maintabs->addViewtoTabContainer("tabs_1", "owner_view");
$owner_view_heading = insertPanel($owner_view,"","");

$tenant_view = $maintabs->addViewtoTabContainer("tabs_2", "tenant_view");
$tenant_view_heading = insertPanel($tenant_view,"","");

//////////// Owner Accordion starts ///////////////
$owner_view_accordion = new jqueryui_accordion_widget("owner_accordion");
$owner_view_accordion->addClass("genereic_accordion");
$owner_view_accordion->setParent($owner_view);
$owner_view_accordion->insertTab("owner_tab","Owner Details");
$owner_view_accordion_tab_cont = $owner_view_accordion->addViewtoTabContainer("owner_tab","netlist_view");
//////////// Owner Accordion ends ///////////////

/*
$tenant_view_accordion = new jqueryui_accordion_widget("tenant_accordion");
$tenant_view_accordion->addClass("genereic_accordion");
$tenant_view_accordion->setParent($tenant_view);
$tenant_view_accordion->insertTab("tenant_tab","Tenant Details");
$tenant_view_accordion_tab_cont = $tenant_view_accordion->addViewtoTabContainer("tenant_tab","netlist_view");
$owner_view_accordion_ps1 = insertPageSection  ($tenant_view_accordion_tab_cont, '', '{"class":"page-section"}');
*/
//////////// Tenant Accordion starts ///////////////

$tenant_view_accordion = new jqueryui_accordion_widget("tenant_accordion");
$tenant_view_accordion->addClass("genereic_accordion");
$tenant_view_accordion->setParent($tenant_view);
if($user_type != 4){
    $tenant_view_accordion->insertTab("tenant_tab","No Tenants");
    $tenant_view_accordion_tab_cont = $tenant_view_accordion->addViewtoTabContainer("tenant_tab","netlist_view");
    
    $tenant_view_accordion_ps1 = insertPageSection  ($tenant_view_accordion_tab_cont, '', '{"class":"page-section"}');
    //insertPanel($tenant_view_accordion_ps1, '{"id":"setup_prop"}',"");
    //insertPanel($tenant_view_accordion_ps1, '{"id":"", "class":"ajax_err_show"}',"" );
    //$tenant_dets_table_container = insertPanel($tenant_view_accordion_ps1,'',"");
    $add_tenant_container = insertElement($tenant_view_accordion_ps1,"div",'{"id":"add_tenant_container_style"}',"");

}else if($user_type == 4){
    $tenant_view_accordion->insertTab("tenant_tab","Tenant Details");
    $tenant_view_accordion_tab_cont = $tenant_view_accordion->addViewtoTabContainer("tenant_tab","netlist_view");
}

//////////// Tenant Accordion ends ///////////////

$owner_view_accordion_ps1 = insertPageSection  ($owner_view_accordion_tab_cont, '', '{"class":"page-section"}');
insertPanel($owner_view_accordion_ps1, '{"id":"setup_prop"}',"");

insertPanel($owner_view_accordion_ps1, '{"id":"", "class":"ajax_err_show"}',"" );

$owner_dets_table_container = insertPanel($owner_view_accordion_ps1,'',"");
$owner_dets_table_container->setAttribute('id',"owner_dets_table_container");
insertPanel($owner_view_accordion_ps1, '{"id":"owner_dets_table_btn_cont"}',"" );

$owner_dets_table = new sense_table([
                                'id'=>'owner_dets_table',
                                'widgetStyle'=>'wdg_sty',
                                'headingStyle'=>'heading_sty',
                                'headingText' => 'Test Table Heading Text',
                                'headingTextStyle' => 'heading-text-sty',
                                'contentTableStyle' => '',
                                'contentStyle' => 'own_det_table_wrapper'
                              ]);

$owner_dets_table->setParent($owner_dets_table_container);
$owner_dets_table->setDB_Conn($conn);
$owner_dets_table->setTableDataUserID($logged_user_id);

$row_obj = $owner_dets_table->addRow();
$owner_dets_table->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');

$cell_obj = $owner_dets_table->addCell("", TRUE);
$owner_dets_table->setCurrCellID("cell_1");

$cell_obj = $owner_dets_table->addCell("Owner", TRUE);
$owner_dets_table->setCurrCellID("cell_2");

$cell_obj = $owner_dets_table->addCell("", TRUE);
$owner_dets_table->setCurrCellID("cell_3");

$cell_obj = $owner_dets_table->addCell("Details", TRUE);
$owner_dets_table->setCurrCellID("cell_4");

$cell_obj = $owner_dets_table->addCell("", TRUE);
$owner_dets_table->setCurrCellID("cell_5");

$cell_obj = $owner_dets_table->addCell("", TRUE);
$owner_dets_table->setCurrCellID("cell_6");

$cell_obj = $owner_dets_table->addCell("", TRUE);
$owner_dets_table->setCurrCellID("cell_7");

$cell_obj = $owner_dets_table->addCell("", TRUE);
$owner_dets_table->setCurrCellID("cell_8");


//first name row starts
/*
$row_obj = $owner_dets_table->addRow();
$owner_dets_table->setRowID($row_obj, "row_1");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');

$cell_obj = $owner_dets_table->addCell("");
$owner_dets_table->setCurrCellID("cell_1_1");

$cell_obj = $owner_dets_table->addCell("First Name");
$owner_dets_table->setCurrCellID("cell_1_2");

$cell_obj = $owner_dets_table->addCell("");
$owner_dets_table->setCurrCellID("cell_1_3");

$cell_obj = $owner_dets_table->addCell("");
$owner_dets_table->setCurrCellID("cell_1_4");
//$first_name_input = $doc->createElement('input');
$first_name_parent_cont = $doc->createElement('div');
$owner_dets_table->insertUserInputElementIntoCurrCell($first_name_parent_cont, "first_name", "", "", '{"ajax":"1", "type":"string", "type_check":"1", "maxlen_check":"1", "maxlen":"8"}' );
$child1 = insertElement($first_name_parent_cont,"div",'{"class":"heading_left_child_cont_style"}', "");
$child2 = insertElement($first_name_parent_cont,"div",'{"class":"heading_left_child_cont_style"}', "");
$first_name_input = createUserInputElement ("input", $child1, $child2, $doc, $conn, 1);
*/


/////======================= User Profile Table: Start ==============================
sec_clear_map ("fld_sig_map");
sec_clear_map ("perm_sig_map");

$user_name_control_elem_obj = $owner_dets_table->addControElement("USER_NAME" , $logfile);
$first_name_row_obj = create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_FN", "First Name", "fn", 1, '{"elemTag":"input"}', $logfile, $user_name_control_elem_obj);




create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_LN", "Last Name", "ln" , 1, '{"elemTag":"input" }', $logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_GENDER", "Gender", "gen" , 1, '{"elemTag":"select","callback": "populate_master_gender", "callbackParam":[] }', $logfile);
$gender_tbl_cell = $doc->getElementById("cell_gen_4");
$gender_select_elem = (($gender_tbl_cell->firstChild)->firstChild)->firstChild;
$gender_select_id = $gender_select_elem->getAttribute("id");
//echo "country_select_id=".$country_select_id."<br>";
$owner_dets_table->setData('{"gender":"'.$gender_select_id.'"}');

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_DOB", "Date of Birth", "dob", 1, '{"elemTag":"input" }', $logfile);

$comm_addr_control_elem_obj = $owner_dets_table->addControElement("COM_ADDRESS", $logfile);
create_user_details_table_row($owner_dets_table, $doc, $conn, "", "Communication Address", "comm_add", 0, '{"elemTag":"input" }', $logfile, $comm_addr_control_elem_obj); 

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_FLATNO", "Flat Num", "fl_num" , 1, '{"elemTag":"input" }', $logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_PROP_NAME", "Property Name", "prop_name", 1, '{"elemTag":"input" }', $logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_ADD_STR1", "Street 1", "st1", 1, '{"elemTag":"input" }', $logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_ADD_STR2", "Street 2", "st2", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_ADD_LOC", "Locality", "loc", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_ADD_COUNTRY", "Country", "country", 1, '{"elemTag":"select" ,"callback": "populate_master_country" , "callbackParam":[]}',$logfile);

$country_tbl_cell = $doc->getElementById("cell_country_4");
$country_select_elem = (($country_tbl_cell->firstChild)->firstChild)->firstChild;
$country_select_id = $country_select_elem->getAttribute("id");
//echo "country_select_id=".$country_select_id."<br>";
$owner_dets_table->setData('{"country":"'.$country_select_id.'"}');

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_ADD_STATE", "State", "state" , 1, '{"elemTag":"select" ,"callback": "populate_master_state" , "callbackParam":[]}',$logfile);
$state_tbl_cell = $doc->getElementById("cell_state_4");
$state_select_elem = (($state_tbl_cell->firstChild)->firstChild)->firstChild;
$state_select_id = $state_select_elem->getAttribute("id");
addDataToElement($country_select_elem, '{"state":"'.$state_select_id.'"}');

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_ADD_CITY", "City", "city", 1, '{"elemTag":"select" ,"callback": "populate_master_city" , "callbackParam":[]}',$logfile);
$city_tbl_cell = $doc->getElementById("cell_city_4");
$city_select_elem = (($city_tbl_cell->firstChild)->firstChild)->firstChild;
$city_select_id = $city_select_elem->getAttribute("id");
addDataToElement($state_select_elem, '{"city":"'.$city_select_id.'"}');

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_ADD_PCODE", "Postal Code", "p_code", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_PH_1", "Phone Number 1", "ph1", 1, '{"elemTag":"input" }', $logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_PH1_CCODE", "Country Code", "cc1", 1, '{"elemTag":"select" ,"callback": "populate_master_country_code" , "callbackParam":[] , "class": "country_code_dd_style"}', $logfile);
$cc1_tbl_cell = $doc->getElementById("cell_cc1_4");
$cc1_select_elem = (($cc1_tbl_cell->firstChild)->firstChild)->firstChild;
$cc1_select_id = $cc1_select_elem->getAttribute("id");
$owner_dets_table->setData('{"code-1":"'.$cc1_select_id.'"}');

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_PH_2", "Phone Number 2", "ph2", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_PH2_CCODE", "Country Code", "cc2", 1, '{"elemTag":"select" ,"callback": "populate_master_country_code" , "callbackParam":[] , "class": "country_code_dd_style"}', $logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_PH_3", "Phone Number 3", "ph3", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_PH3_CCODE", "Country Code", "cc3", 1, '{"elemTag":"select" ,"callback": "populate_master_country_code" , "callbackParam":[] , "class": "country_code_dd_style"}',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_PH_4", "Phone Number 4", "ph4", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_PH4_CCODE", "Country Code", "cc4", 1, '{"elemTag":"select" ,"callback": "populate_master_country_code" , "callbackParam":[] , "class": "country_code_dd_style"}',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_BLD_GRP", "Blood Group", "bld_grp", 1, '{"elemTag":"select" ,"callback": "populate_master_blood_grp" , "callbackParam":[]}',$logfile);
$dob_tbl_cell = $doc->getElementById("cell_bld_grp_4");
$dob_select_elem = (($dob_tbl_cell->firstChild)->firstChild)->firstChild;
$dob_select_id = $dob_select_elem->getAttribute("id");
//echo "country_select_id=".$country_select_id."<br>";
$owner_dets_table->setData('{"bld_grp":"'.$dob_select_id.'"}');

create_user_details_table_row($owner_dets_table, $doc, $conn, "", "Emergency Information", "emg_info", 0, '{"elemTag":"input" }',$logfile); 

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_EMG_PH1", "Emergency Phone 1", "emg_ph1", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_EMG_PH1_CCODE", "Country Code", "emg_cc1", 1, '{"elemTag":"select","callback": "populate_master_country_code" , "callbackParam":[] , "class": "country_code_dd_style"}',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_EMG_PH1_NAME", "Name", "emg_nm1", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_EMG_PH1_REL", "Relation", "emg_rel1", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_EMG_PH2", "Emergency Phone 2", "emg_ph2" , 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_EMG_PH2_CCODE", "Country Code", "emg_cc2" , 1, '{"elemTag":"select" ,"callback": "populate_master_country_code" , "callbackParam":[] , "class": "country_code_dd_style"}',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_EMG_PH2_NAME", "Name", "emg_nm2", 1, '{"elemTag":"input" }',$logfile);

create_user_details_table_row($owner_dets_table, $doc, $conn, "USRPROF_EMG_PH2_REL", "Relation", "emg_rel2" , 1, '{"elemTag":"input" }',$logfile);


$sql_phone_code = "SELECT * FROM master_phone_code";
$phone_code_temp = $conn->prepare($sql_phone_code);
//$phone_code_temp->bind_param("i",$aa);
$phone_code_temp->execute();
$phone_code_result = $phone_code_temp->get_result();
$phone_code_result_row = $phone_code_result->fetch_all();
$numofrows = mysqli_num_rows($phone_code_result);

$country_array = array();
foreach ($phone_code_result_row as $value){  
    foreach ($value as $k => $v){
        $phone_code_id = $value["0"];
        $country_id = $value["1"];
        $phone_code = $value["2"];
    }
    //$seced_permSigPriID = sec_push_val_single_entry ("phone_code_sig_map", $phone_code_id);

    $check_mast_country = "SELECT * FROM master_country WHERE id = ?";
    $check_mast_country_temp = $conn->prepare($check_mast_country);
    $check_mast_country_temp->bind_param("i",$country_id);
    $check_mast_country_temp->execute();
    $check_mast_country_result = $check_mast_country_temp->get_result();
    $mast_country_result_row = $check_mast_country_result->fetch_all();
    $numofrows = mysqli_num_rows($check_mast_country_result);
    foreach ($mast_country_result_row as $value){  
        foreach ($value as $k => $v){
            $country_code = $value["2"];
        }
    }
    
    $phoneCode = $phone_code."(".$country_code.")";

    $cc2_tbl_cell = $doc->getElementById("cell_cc2_4");
    $cc2_select_elem = (($cc2_tbl_cell->firstChild)->firstChild)->firstChild;
    $cc2_option = $doc->createElement("option");
    $cc2_option->textContent = $phoneCode;
    $cc2_select_elem->appendChild($cc2_option);

    $cc3_tbl_cell = $doc->getElementById("cell_cc3_4");
    $cc3_select_elem = (($cc3_tbl_cell->firstChild)->firstChild)->firstChild;
    $cc3_option = $doc->createElement("option");
    $cc3_option->textContent = $phoneCode;
    $cc3_select_elem->appendChild($cc3_option);

    $cc4_tbl_cell = $doc->getElementById("cell_cc4_4");
    $cc4_select_elem = (($cc4_tbl_cell->firstChild)->firstChild)->firstChild;
    $cc4_option = $doc->createElement("option");
    $cc4_option->textContent = $phoneCode;
    $cc4_select_elem->appendChild($cc4_option);

//emergency country code dropdown

    $ecc1_tbl_cell = $doc->getElementById("cell_emg_cc1_4");
    $ecc1_select_elem = (($ecc1_tbl_cell->firstChild)->firstChild)->firstChild;
    $ecc1_option = $doc->createElement("option");
    $ecc1_option->textContent = $phoneCode;
    $ecc1_select_elem->appendChild($ecc1_option);

    $ecc2_tbl_cell = $doc->getElementById("cell_emg_cc2_4");
    $ecc2_select_elem = (($ecc2_tbl_cell->firstChild)->firstChild)->firstChild;
    $ecc2_option = $doc->createElement("option");
    $ecc2_option->textContent = $phoneCode;
    $ecc2_select_elem->appendChild($ecc2_option);  
}

//************ */blood group drop down starts

$sql_bloodgrp = "SELECT * FROM master_blood_group";
$bloodgrp_temp = $conn->prepare($sql_bloodgrp);
$bloodgrp_temp->execute();
$bloodgrp_result = $bloodgrp_temp->get_result();
$phone_code_result_row = $bloodgrp_result->fetch_all();
$ph_code_numofrows = mysqli_num_rows($bloodgrp_result);
foreach ($phone_code_result_row as $value){  
    foreach ($value as $k => $v){
        $blood_grp = $value["1"];
    }
    //echo "the blood group is:: ".$blood_grp."</br>";
    $bld_grp_tbl_cell = $doc->getElementById("cell_bld_grp_4");
    $bld_grp_select_elem = (($bld_grp_tbl_cell->firstChild)->firstChild)->firstChild;
    $bld_grp_option = $doc->createElement("option");
    $bld_grp_option->textContent = $blood_grp;
    $bld_grp_select_elem->appendChild($bld_grp_option);
}

//*********** */blood group drop down ends


$sql_gen = "SELECT * FROM master_gender";
$gen_temp = $conn->prepare($sql_gen);
$gen_temp->execute();
$gen_result = $gen_temp->get_result();
$gen_result_row = $gen_result->fetch_all();
$ph_code_numofrows = mysqli_num_rows($gen_result);
foreach ($gen_result_row as $value){  
    foreach ($value as $k => $v){
        $gender = $value["1"];
    }
    //echo "the blood group is:: ".$blood_grp."</br>";
    $gen_tbl_cell = $doc->getElementById("cell_gen_4");
    $gen_select_elem = (($gen_tbl_cell->firstChild)->firstChild)->firstChild;
    $gen_option = $doc->createElement("option");
    $gen_option->textContent = $gender;
    $gen_select_elem->appendChild($gen_option);
}


$curr_map = sec_get_map("fld_sig_map");
/*
$logfile->logfile_writeline(__FILE__."---Dumping fld_sig_map MAP: Begin");
        foreach($curr_map as $key => $value)
            {
                $logfile->logfile_writeline($key." : ".$value);
            }
$logfile->logfile_writeline(__FILE__."---Dumping fld_sig_map MAP: End");
*/

function CurlSendPostJson($url,$datajson){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datajson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($datajson)));
    //curl_setopt($ch,CURLOPT_HEADER, true); //if you want headers
    return $result = curl_exec($ch);
}

$actl_curl_url;

$data=array("param"=>array("proj_id"=>"2","role_id"=>"3","perm_grp_id"=>"4","perm_sig"=>array("a","b")));

$data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
echo CurlSendPostJson($actl_curl_url, $data_str);


//fill_form_value($doc, $curr_map, $conn, $logfile);



//$owner_dets_table->fill_UserInput_value($curr_map, NULL, NULL);




///////////////////////////////////////////////////////////////
////////START OF DIALOGUES
///////////////////////////////////////////////////////////////
$dialog_parent = $doc_body;

$add_tenant_dialog = insertPanel($dialog_parent, '{"id":"user_dets_dialog", "class":"add_tenant_dialog_style", "title":"Add tenant", "data-op": "addtenant"}',"");
$add_tenant_dialog_parent_div = insertElement($add_tenant_dialog,"div",'{"class":"user_dets_parent_div_style", "data-op": "addtenant"}', "");

unit_dialog($add_tenant_dialog_parent_div);

///////////////////////////////////////////////////////////////
////////END OF DIALOGUES
///////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////
////////Fetch Permission
///////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////
////////Apply Permission
///////////////////////////////////////////////////////////////


echo $doc->saveHTML();

$logfile->logfile_close();
?>



