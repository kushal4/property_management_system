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
//require_once '../lib/php-lib/Log.php';
//include 'sec.php';
$session_val= is_session_valid();
if($session_val==0){
}
else{
    header("Location: eecee_login.php");
}

//include 'secmap.php';


//$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
//$logfile->logfile_open("w");
//$fle_handle=fopen("/home/steffi/Steffi_usr/stef/tarantoo/Logs/tarantoo.log","w");
//$fle_handle=fopen("Logs/eecee.log","w");


//echo "before master_layout.php";
include "../lib/php-lib/master_layout.php";
//$user_id=$_SESSION["login_user"];
addIcon($doc_head, "/favicon.ico");

addStyleSheet($doc_head, "themes/home.css?".time());
addStyleSheet($doc_head, "../ext-styles/jquery-ui.css");
addStyleSheet($doc_head, "../styles/vert_tab.css");
//addStyleSheet($doc_head, "themes/style.css");
//addStyleSheet($doc_head, "themes/style_test01.css");
//addStyleSheet($doc_head, "themes/style_test02.css");
addStyleSheet($doc_head, "themes/style_test03.css?".time());

addScriptPath($doc_head, "../ext_lib/jquery-3.2.1.js");
addScriptPath($doc_head, "../ext_lib/jquery-ui.js");
addScriptPath($doc_head, "../lib/js-lib/resize.js?".time());
//addScriptPath($doc_head, "lib/tarantoo_glob_var.js?".time());
addScriptPath($doc_head, "../lib/js-lib/sense-lib.js?".time());
//addScriptPath($doc_head, "lib/tarantoo_net.js?".time());
//addScriptPath($doc_head, "lib/tarantoo_instance.js?".time());
//addScriptPath($doc_head, "lib/tarantoo_client.js?".time());
addScriptPath($doc_head, "lib/eecee.js?".time());
addScriptPath($doc_head, "lib/additional.js?".time());
addScriptPath($doc_head, "../lib/js-lib/vert_tab.js?".time());
addScriptPath($doc_head, "lib/js-lib/accordion.js?".time());
addScriptPath($doc_head, "../lib/js-lib/Chart.js?".time());
addScriptPath($doc_head, "../lib/js-lib/table_creator.js?".time());

///////////////////////////////////////////////////////////////
////////MAIN LAYOUT CONTENT: START
///////////////////////////////////////////////////////////////

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["acc_id"];
//echo "the user ID is".$user_id;

$sql_check_prop = "SELECT * FROM sel_prop WHERE user_id = ?";
$sql_check_prop_temp = $conn->prepare($sql_check_prop);
$sql_check_prop_temp->bind_param("i",$user_id);
$sql_check_prop_temp->execute();
$sql_check_prop_result = $sql_check_prop_temp->get_result();
$sql_check_prop_row = $sql_check_prop_result->fetch_all();
//print_r ($sql_check_prop_row);
//echo "<br>";
$count_sql_check_prop = $sql_check_prop_result->num_rows;
//echo "the number of rows are".$count_sql_check_prop;
//echo "<br>"."*******"."<br>";
if( $count_sql_check_prop > 0){


    $sel_prop_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
    $sel_prop_pg_sec = insertPageSection  ($sel_prop_container, 'Your are associated with the following properties. Please click on one to select.', '{"class":"sel-prop-page-section"}');

    $sel_prop_table_container = insertPanel($sel_prop_pg_sec,'',"");
    $sel_prop_table_container->setAttribute('id',"sel_prop_tbl_container");
    addAttribToElement($sel_prop_table_container, '{"class":"sel_prop_tbl_container_styl"}');

    $sel_prop_table = new sense_table([
        //'id'=>'test_tbl',
        'widgetStyle'=>'wdg_sty',
        'headingStyle'=>'heading_sty',
        'headingText' => 'Test Table Heading Text',
        'headingTextStyle' => 'heading-text-sty',
        'contentTableStyle' => 'generic_table',
        'contentStyle' => 'table_wrapper'
        
      ]);
      $sel_prop_table->setParent($sel_prop_table_container);

        $row_obj = $sel_prop_table->addRow();
        $sel_prop_table->setRowID($row_obj, "row_0");
        addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        $cell_obj = $sel_prop_table->addCell("Property Names", TRUE);
        $sel_prop_table->setCurrCellID("cell_1");

    foreach ($sql_check_prop_row as $value){  
       //$property_id = $sql_check_prop_row["prop_id"];
       //$property_name = $sql_check_prop_row["prop_name"];
       //print_r ($value);
       //echo "<br>"."*******"."<br>";
       foreach ($value as $k => $v){
        //$property_name = $v["3"];
        //echo "{$k} => {$v} ";
        //$key1 = $value["1"];
        $name = $value["3"];
       }
       //echo "the prop name is::".$name;

       $row_obj = $sel_prop_table->addRow();
       $sel_prop_table->setRowID($row_obj, "row_1");
       addAttribToElement($row_obj, '{"class":"prop_names_styl"}');
       $cell_obj = $sel_prop_table->addCell($name);
       $sel_prop_table->setCurrCellID("cell_1");
       
       
    }
    
    
}
else{

    $sel_prop_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
    $sel_prop_pg_sec = insertPageSection  ($sel_prop_container, 'Your are not associated with any properties. Please click on the below button to create properties.', '{"class":"sel-prop-page-section"}');

    $create_prop_button = insertElement($sel_prop_pg_sec,"button",'{"id":"create_prop_butt","class":"create_prop_butt_styl"}'," Create Property");  

    

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