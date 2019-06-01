<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
//echo "opening dev.php";
$log_path = "Logs/eecee.log";
include 'lib/php-lib/eecee_constants.php';
include '../lib/php-lib/session_exp.php';
include '../lib/php-lib/dom_func.php';
require '../lib/php-lib/composite_control_classes.php';
include '../lib/php-lib/reg_func.php';
include '../lib/php-lib/sec.php';
include 'lib/php-lib/eecee_sec_map.php';
//require_once '../lib/php-lib/Log.php';
//include 'sec.php';
$session_val= is_session_valid();
if($session_val==0){
}
else{
    header("Location: eecee_login.php");
}

include "../lib/php-lib/master_layout.php";
//$user_id=$_SESSION["login_user"];
addIcon($doc_head, "/favicon.ico");

addStyleSheet($doc_head, "themes/home.css?".time());
addStyleSheet($doc_head, "../ext-styles/jquery-ui.css");
addStyleSheet($doc_head, "../styles/vert_tab.css");
addStyleSheet($doc_head, "../ext-styles/themes/default/style.css");
addStyleSheet($doc_head, "../ext-styles/themes/default-dark/style.css");
addStyleSheet($doc_head, "themes/style_test03.css?".time());

addScriptPath($doc_head, "../ext_lib/js-lib/jquery-3.2.1.js");
addScriptPath($doc_head, "../ext_lib/js-lib/jquery-ui.js");
addScriptPath($doc_head, "../lib/js-lib/resize.js?".time());
addScriptPath($doc_head, "../ext_lib/js-lib/jstree.js");
//addScriptPath($doc_head, "lib/tarantoo_glob_var.js?".time());
addScriptPath($doc_head, "../lib/js-lib/sense-lib.js?".time());
addScriptPath($doc_head, "lib/eecee.js?".time());
addScriptPath($doc_head, "lib/additional.js?".time());
addScriptPath($doc_head, "../lib/js-lib/vert_tab.js?".time());
addScriptPath($doc_head, "lib/js-lib/accordion.js?".time());
addScriptPath($doc_head, "lib/js-lib/sel_prop.js?".time());
addScriptPath($doc_head, "../lib/js-lib/Chart.js?".time());
addScriptPath($doc_head, "../lib/js-lib/table_creator.js?".time());

///////////////////////////////////////////////////////////////
////////MAIN LAYOUT CONTENT: START
///////////////////////////////////////////////////////////////

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];
//echo "the user ID is:".$user_id;

/*
$sql_check_prop = "SELECT * FROM contexts WHERE user_id = ?";
$sql_check_prop_temp = $conn->prepare($sql_check_prop);
$sql_check_prop_temp->bind_param("i",$user_id);
$sql_check_prop_temp->execute();
$sql_check_prop_result = $sql_check_prop_temp->get_result();
$sql_check_prop_row = $sql_check_prop_result->fetch_all();
*/

$sql_check_prop = "SELECT * FROM properties WHERE created_by = ?";
$sql_check_prop_temp = $conn->prepare($sql_check_prop);
$sql_check_prop_temp->bind_param("i",$user_id);
$sql_check_prop_temp->execute();
$sql_check_prop_result = $sql_check_prop_temp->get_result();
$sql_check_prop_row = $sql_check_prop_result->fetch_all();


$count_sql_check_prop = $sql_check_prop_result->num_rows;

if( $count_sql_check_prop > 0){


    $user_prop_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
    //left page section starts
    //$user_prop_pg_sec = insertElement($user_prop_container, 'Your are associated with the following properties. Please click on one to select.', '{"class":"sel-prop-page-section sel_prop_left_pg_sec_style"}');
    $user_prop_pg_sec = insertElement($user_prop_container,"div",'{"id":"","class":"sel_prop_vert_split_style"}',"Your are associated with the following properties. Please click on one to select");
    //$heading_div = $user_prop_pg_sec->firstChild;
    //addAttribToElement($heading_div, '{"class":"sel_prop_pg_sec_heading_style"}');

    $user_prop_table_container = insertPanel($user_prop_pg_sec,'',"");
    $user_prop_table_container->setAttribute('id',"sel_prop_tbl_container");
    addAttribToElement($user_prop_table_container, '{"class":"sel_prop_tbl_container_styl"}');

    $existing_prop_dd_cont = insertElement($user_prop_pg_sec,"div",'{"id":"existing_prop_dd_cont","class":"existing_prop_dd_cont_style"}',"");
    $existing_prop_dropdown = insertElement($existing_prop_dd_cont,"select",'{"id":"existing_prop_dropdown","class":"existing_prop_dropdown_style"}',"");
    insertElement($existing_prop_dropdown,"option",'{"id":"","class":"existing_prop_dropdown_style", "value":"0"}',"Select Property"); 
    
    //$create_new_prop_button = insertElement($existing_prop_dd_cont,"button",'{"id":"create_prop_butt","class":"create_prop_butt_styl"}'," Create New Property");  

    $role_table_cont = insertElement($user_prop_pg_sec,"div",'{"id":"role_table_cont","class":"role_table_cont"}',"");

    $login_role_jstree_cont = insertElement($user_prop_pg_sec,"div",'{"id":"login_role_tree","class":"login_role_tree_style"}',"");
    //$test_btn = insertElement($user_prop_pg_sec,"button",'{"id":"test_btn","class":""}',"Test");
    //left page section ends

    //right page section starts
    //$permission_pg_sec = insertElement  ($user_prop_container, '', '{"class":"sel-prop-page-section"}');
    $permission_pg_sec = insertElement($user_prop_container,"div",'{"id":"","class":"sel_prop_vert_split_style"}',"");

    $permission_neading_cont = insertElement($permission_pg_sec,"div",'{"id":"permission_neading_cont","class":"permission_neading_cont_style"}',"Role Permission");

    $enter_with_role_button_cont = insertElement($permission_pg_sec,"div",'{"id":"enter_with_role_button_cont","class":"enter_with_role_button_cont_style"}',"");
    $enter_with_role_button = insertElement($enter_with_role_button_cont,"button",'{"id":"enter_with_role_button","class":"enter_with_role_button_styl"}'," Enter with this Role");  
    
    $login_view_perm_cont = insertElement($permission_pg_sec,"div",'{"id":"login_view_perm_cont","class":"login_view_perm_cont_style"}',"");
    
    //$create_new_prop_button = insertElement($permission_pg_sec,"button",'{"id":"create_prop_butt","class":"create_prop_butt_styl"}'," Create New Property");  
    //right page section ends

    //$login_role_jstree_cont = insertElement($user_prop_container,"div",'{"id":"login_role_tree","class":"login_role_tree_style"}',"");
    //$permission_pg_sec = insertElement($user_prop_container,"div",'{"id":"","class":"sel_prop_vert_split_style"}',"");
    $create_new_prop_button = insertElement($user_prop_container,"button",'{"id":"create_prop_butt","class":"create_prop_butt_styl"}'," Create New Property");  

    foreach ($sql_check_prop_row as $value){  
       
        /*
        $pri_id = $value["0"];
        $prop_id = $value["3"];
        $unit_id = $value["4"];
        $user_type = $value["2"];
       
        $prop_topo_arr_return = get_prop_topo_array($conn, $unit_id);
        $prop_topo_arr_row = $prop_topo_arr_return["prop_topo_row"];
        
        $unit_name = $prop_topo_arr_row['node_name'];

        $prop_arr_return = get_properties_array_id($conn, $prop_id);
        $prop_arr_row = $prop_arr_return["prop_row"];
        $prop_name = $prop_arr_row['setup_name'];
        */

        $prop_id = $value["0"];
        $prop_name = $value["1"];

        $secedPropID = sec_push_val_single_entry ("prop_id_map", $prop_id);

        $prop_elem = insertElement($existing_prop_dropdown,"option",'{"id":"","class":""}',$prop_name);
        $prop_elem->setAttribute('value',$secedPropID);
    }
    
}
else{

    $user_prop_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
    $user_prop_pg_sec = insertPageSection  ($user_prop_container, 'Your are not associated with any properties. Please click on the below button to create properties.', '{"class":"sel-prop-page-section"}');
    //addAttribToElement($user_prop_pg_sec, '{"class": "test_heading_class" }');
    $abc = $user_prop_pg_sec->first_child;

    if ($item = $user_prop_pg_sec->firstChild) {
       echo "yes";
    }

    $create_prop_button = insertElement($user_prop_pg_sec,"button",'{"id":"create_prop_butt","class":"create_prop_butt_styl"}'," Create Property");  

    

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

//$logfile->logfile_close();

?>
