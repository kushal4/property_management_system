
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."eecee.log";
require_once $sense_common_php_lib_path.'Log.php';
//include 'lib/php-lib/eecee_constants.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
//include '../lib/php-lib/reg_func.php';
include $sense_common_php_lib_path.'reg_func.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
//include 'lib/php-lib/eecee_sec_map.php';

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

addScriptPath($doc_head, $eecee_js_lib_path."accordion.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); 
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());

addScriptPath($doc_head, $eecee_js_lib_path."common_js_file.js?".time());
addScriptPath($doc_head, "js/helpdesk.js?".time());

// JS Files :: END
// addScriptPath($doc_head,"https://unpkg.com/react@16/umd/react.development.js");
// addScriptPath($doc_head,"https://unpkg.com/react-dom@16/umd/react-dom.development.js");


//addScriptPath($doc_head, "lib/react/react.js?".time());
//addScriptPath($doc_head, "lib/react/react.dom.js?".time());
//addScriptPath($doc_head, "https://unpkg.com/babel-standalone@6/babel.min.js?".time(),"text/babel");

//addScriptPath($doc_head, "lib/react/translator.jsx?".time(),"text/jsx");
//addScriptPath($doc_head, "lib/react/index.jsx?".time());




$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$feature_cat_sig = $_SESSION["FCAT"];
//echo "FCAT=".$feature_cat_sig."<br><br>";
$fea_obj = $_SESSION["fea_obj"];
//var_dump($perm_obj);

$user_id = $_SESSION["user_id"];

//$acc_man_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
//$features_pg_sec = insertPageSection($acc_man_container, '', '{"class":"sel-prop-page-section"}');

$acc_man_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
$features_pg_sec = insertPageSection  ($acc_man_container, '', '{"class":"sel-prop-page-section"}');
$dialog_div1=insertElement($layout_main_container, 'div', '{"class":"","id":"msg_dialog"}',"");



$dialog_msg=insertElement($dialog_div1, 'span', '{"class":"","id":"msg"}',"");

$dialog_div2=insertElement($layout_main_container, 'div', '{"class":"","id":"manage_issue_cat_div"}',"");
$input=insertElement($dialog_div2, 'input', '{"class":"","id":"cat_txt"}',"");
$button=insertElement($dialog_div2, 'button', '{"class":"","id":"add_button"}',"Add");


$feature_table_container = insertPanel($features_pg_sec,'',"");
$feature_table_container->setAttribute('id',"sel_prop_tbl_container");
addAttribToElement($feature_table_container, '{"class":"sel_prop_tbl_container_styl"}');
//$create_new_prop_button = insertElement($features_pg_sec,"button",'{"id":"create_prop_butt","class":"create_prop_butt_styl"}'," Create New Property");  

$featureTable = new sense_table([
    //'id'=>'test_tbl',
    'widgetStyle'=>'wdg_sty',
    'headingStyle'=>'heading_sty',
    'headingText' => 'Test Table Heading Text',
    'headingTextStyle' => 'heading-text-sty',
    'contentTableStyle' => 'generic_table',
    'contentStyle' => 'sel_prop_table_wrapper'
    
]);
$featureTable->setParent($feature_table_container);

$row_obj = $featureTable->addRow();
$featureTable->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');

$cell_obj = $featureTable->addCell("Features", TRUE);
$featureTable->setCurrCellID("cell_1");

//foreach ($perm_obj as $value){  
    foreach ($fea_obj as $v){
        $sig = $v["sig"];
        $fet_cat_name = $v["text"];
        $type = $v["type"];
        $parent = $v["parent"];
        $feature_name = $v["text"];
        
        if($type == "feature" && $parent == $feature_cat_sig){
            $secedFeatCatSig = sec_push_val_single_entry ("feat_sig_map", $sig);
            //echo "the sig is::".$sig."</br>";
            //echo "the parent is::".$parent."</br>";
            //echo "the type is::".$type."</br>";
            //echo "the feature name is::".$feature_name;
            //echo "</br>";

            $row_obj = $featureTable->addRow();
            $featureTable->setRowID($row_obj, "row_".$sig);
            addAttribToElement($row_obj, '{"class":""}');
            addAttribToElement($row_obj, '{"data-prop_id": '.$sig.' }');

            $cell_obj = $featureTable->addCell($feature_name);
            $featureTable->setCurrCellID("cell".$secedFeatCatSig."_1");
            addAttribToElement($cell_obj, '{"class":"Feature_name_style"}');
            //addAttribToElement($cell_obj, '{"data-prop_id": '.$sig.' }');
            $lower_sig = strtolower($sig);
            //echo "the lower sig is".$lower_sig."</br>";
            addDataToElement($cell_obj, '{"j":"'.$lower_sig.'"}');
            addDataToElement($cell_obj, '{"s":"'.$secedFeatCatSig.'"}');
        }
    }
//}

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

