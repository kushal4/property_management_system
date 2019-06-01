<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_sec_map.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';

include $sense_common_php_lib_path.'reg_func.php';
include $sense_common_php_lib_path.'sec.php';


$logfile = new \Sense\Log("../Logs/eecee.log", __FILE__);
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
//addScriptPath($doc_head, "../../../sense_common_lib/lib/js-lib/table_creator.js?".time());


addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); // rr
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());

addScriptPath($doc_head, "sel_prop.js?".time());
// JS Files:: END

///////////////////////////////////////////////////////////////
////////MAIN LAYOUT CONTENT: START
///////////////////////////////////////////////////////////////

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$logfile->logfile_writeline("getting inside sel_prop.php"); 

$user_id = $SENSESSION->get_val("user_id");

$sql_check_prop = "SELECT DISTINCT prop_id FROM contexts WHERE user_id = ?";
$sql_check_prop_temp = $conn->prepare($sql_check_prop);
$sql_check_prop_temp->bind_param("i",$user_id);
$sql_check_prop_temp->execute();
$sql_check_prop_result = $sql_check_prop_temp->get_result();
$sql_check_prop_row = $sql_check_prop_result->fetch_all();


/*
$sql_check_prop = "SELECT * FROM properties WHERE created_by = ?";
$sql_check_prop_temp = $conn->prepare($sql_check_prop);
$sql_check_prop_temp->bind_param("i",$user_id);
$sql_check_prop_temp->execute();
$sql_check_prop_result = $sql_check_prop_temp->get_result();
$sql_check_prop_row = $sql_check_prop_result->fetch_all();
*/

$count_sql_check_prop = $sql_check_prop_result->num_rows;

if( $count_sql_check_prop > 0){

    $user_prop_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  // main div

    //left page section starts
    $user_prop_pg_sec = insertElement($user_prop_container,"div",'{"id":"","class":"sel_prop_vert_split_style"}',"Your are associated with the following properties. Please click on one to select");
    
    $existing_prop_parent_cont = insertElement($user_prop_pg_sec,"div",'{"id":"existing_prop_parent_cont","class":"existing_prop_parent_cont_style"}',"");
    $existing_prop_lbl = insertElement($existing_prop_parent_cont,"div",'{"id":"existing_prop_lbl","class":"existing_prop_lbl_style"}',"");
    $existing_prop_span = insertElement($existing_prop_lbl,"span",'{"id":"existing_prop_span","class":"existing_prop_span_style"}',"Properties");
    $existing_prop_dd_cont = insertElement($existing_prop_parent_cont,"div",'{"id":"existing_prop_dd_cont","class":"existing_prop_dd_cont_style"}',"");
    $existing_prop_dropdown = insertElement($existing_prop_dd_cont,"select",'{"id":"existing_prop_dropdown","class":"existing_prop_dropdown_style"}',"");
    insertElement($existing_prop_dropdown,"option",'{"id":"","class":"existing_prop_dropdown_style", "value":"0"}',"Select Property"); 

    $context_role_dd_parent_cont = insertElement($user_prop_pg_sec,"div",'{"id":"context_role_dd_parent_cont","class":"context_role_dd_parent_cont_style"}',"");
    $context_role_lbl = insertElement($context_role_dd_parent_cont,"div",'{"id":"context_role_lbl","class":"context_role_lbl_style"}',"");
    $context_role_span = insertElement($context_role_lbl,"span",'{"id":"context_role_span","class":"context_role_span_style"}',"Capacity (Assigned Role)");
    $context_role_dd_cont = insertElement($context_role_dd_parent_cont,"div",'{"id":"context_role_dd_cont","class":"context_role_dd_cont_style"}',"");
    $context_role_dropdown = insertElement($context_role_dd_cont,"select",'{"id":"context_role_dropdown","class":"context_role_dropdown_style"}',"");
    insertElement($context_role_dropdown,"option",'{"id":"","class":"context_role_dropdown", "value":"0"}',"Select role");
    
    //$role_table_cont = insertElement($user_prop_pg_sec,"div",'{"id":"role_table_cont","class":"role_table_cont"}',"");
    $login_role_jstree_parent_cont = insertElement($user_prop_pg_sec,"div",'{"id":"login_role_jstree_parent_cont","class":"login_role_jstree_parent_cont_style"}',"");
    $login_role_jstree_cont = insertElement($login_role_jstree_parent_cont,"div",'{"id":"login_role_tree","class":"login_role_tree_style"}',"");

    //foreach ($sql_check_prop_row as $value){  
        //$prop_id = $value["0"];
        $sql_check_prop = "SELECT * FROM properties";
        $sql_check_prop_temp = $conn->prepare($sql_check_prop);
        //$sql_check_prop_temp->bind_param("i",$prop_id);
        $sql_check_prop_temp->execute();
        $sql_check_prop_result = $sql_check_prop_temp->get_result();
        $sql_check_prop_row = $sql_check_prop_result->fetch_assoc();
        $propid = $sql_check_prop_row["id"];
        //echo "the prop name is :: ".$prop_name."<br>";
        $prop_name = $sql_check_prop_row["setup_name"];
        //echo "the propid is :: ".$propid."<br>";

        $secedPropID = sec_push_val_single_entry ("prop_id_map", $propid);
        $prop_elem = insertElement($existing_prop_dropdown,"option",'{"id":"","class":""}',$prop_name);
        $prop_elem->setAttribute('value',$secedPropID);

        /*
        $sql_check_prop_all = $sql_check_prop_result->fetch_all();
        foreach ($sql_check_prop_all as $key=>$value){  
            $prop_name = $value[1];
            $propid = $value[0];
            $secedPropID = sec_push_val_single_entry ("prop_id_map", $propid);
            $prop_elem = insertElement($existing_prop_dropdown,"option",'{"id":"","class":""}',$prop_name);
            $prop_elem->setAttribute('value',$secedPropID);
        }
        */
    //}
    //left page section ends

    //right page section starts
    $permission_pg_sec = insertElement($user_prop_container,"div",'{"id":"","class":"sel_prop_vert_split_style"}',"");

    $permission_neading_cont = insertElement($permission_pg_sec,"div",'{"id":"permission_neading_cont","class":"permission_neading_cont_style"}',"Role Permission");
    
    $login_view_perm_cont = insertElement($permission_pg_sec,"div",'{"id":"login_view_perm_cont","class":"login_view_perm_cont_style"}',"");

    $enter_with_role_button_cont = insertElement($permission_pg_sec,"div",'{"id":"enter_with_role_button_cont","class":"enter_with_role_button_cont_style"}',"");
    $enter_with_role_button = insertElement($enter_with_role_button_cont,"button",'{"id":"enter_with_role_button","class":"enter_with_role_button_styl"}'," Enter with this Role");  

    $create_new_prop_button_cont = insertElement($user_prop_container,"div",'{"id":"create_new_prop_button_cont","class":"create_new_prop_button_cont_style"}',"");
    $create_new_prop_button = insertElement($create_new_prop_button_cont,"button",'{"id":"create_prop_butt","class":"create_prop_butt_styl"}'," Create New Property");  
    //right page section ends
    
    
}
else{

    $user_prop_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
    $user_prop_pg_sec = insertPageSection  ($user_prop_container, 'Your are not associated with any properties. Please click on the below button to create properties.', '{"class":"sel-prop-page-section"}');
    //addAttribToElement($user_prop_pg_sec, '{"class": "test_heading_class" }');
    //$abc = $user_prop_pg_sec->first_child;

   // if ($item = $user_prop_pg_sec->firstChild) {
       //echo "yes";
    //}
    $create_new_prop_button_cont = insertElement($user_prop_container,"div",'{"id":"create_new_prop_button_cont","class":"create_new_prop_button_cont_style"}',"");
    $create_new_prop_button = insertElement($create_new_prop_button_cont,"button",'{"id":"create_prop_butt","class":"create_prop_butt_styl"}'," Create New Property");  
    //$create_prop_button = insertElement($user_prop_pg_sec,"button",'{"id":"create_prop_butt","class":"create_prop_butt_styl"}'," Create Property");  

    

    //header("Location: create_prop.php");
}

///////////////////////////////////////////////////////////////
////////MAIN LAYOUT CONTENT: END
///////////////////////////////////////////////////////////////
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
