
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."swfm.log";
require_once $sense_common_php_lib_path.'Log.php';

include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';

include $sense_common_php_lib_path.'reg_func.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
//include 'lib/php-lib/eecee_sec_map.php';

$logfile = new \Sense\Log($log_path, __FILE__);

$logfile->logfile_open("a");

$session_val= $SENSESSION->session_exists();
if(!$session_val){
    header("Location: ../login/eecee_logout.php");
}
include $sense_common_php_lib_path.'master_layout.php';
addIcon($doc_head, "/favicon.ico");
// CSS Files :: START
addStyleSheet($doc_head, $eecee_styles_path."home.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."vert_tab.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."style_test03.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."fea_tbl.css?".time());
addStyleSheet($doc_head, $eecee_styles_path."attrib_tbl.css?".time());
addStyleSheet($doc_head, $eecee_ext_styles_path."jquery-ui.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."themes/default/style.css");
addStyleSheet($doc_head, $eecee_ext_styles_path."/themes/default-dark/style.css");
addStyleSheet($doc_head, "css/swfm.css?".time());
// CSS Files :: END


//JS Files :: START
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-3.2.1.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jquery-ui.js");
addScriptPath($doc_head, $eecee_ext_js_lib_path."jstree.js");
addScriptPath($doc_head, $sense_common_js_lib_path."resize.js?".time());
addScriptPath($doc_head, $sense_common_js_lib_path."sense-lib.js?".time());

addScriptPath($doc_head, $eecee_js_lib_path."eecee.js?".time()); // rr
addScriptPath($doc_head, $eecee_js_lib_path."additional.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."vert_tab.js?".time());
addScriptPath($doc_head, $eecee_js_lib_path."common_js_file.js?".time());
addScriptPath($doc_head, "js/swfm.js?".time());
addScriptPath($doc_head, "../acc/acc_man_role.js?".time());
// JS Files :: END

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$feature_cat_sig =$SENSESSION->get_val("FCAT");

//$logfile->logfile_writeline("FCAT value fetched :".$feature_cat_sig);
//echo "FCAT=".$feature_cat_sig."<br><br>";
$fea_obj = $SENSESSION->get_val("fea_obj");
$logfile->logfile_writeline("\n\n ------------------Fetching feature tree-------------- \n\n");
//var_dump($perm_obj);
$logfile->logfile_writeline(print_r($fea_obj,true));
$user_id = $SENSESSION->get_val("user_id");

//$acc_man_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
//$features_pg_sec = insertPageSection($acc_man_container, '', '{"class":"sel-prop-page-section"}');

$acc_man_container = insertElement($layout_main_container,"div",'{"class":"sel_prop_div"}'," ");  
$features_pg_sec = insertPageSection  ($acc_man_container, '', '{"class":"sel-prop-page-section"}');

  
 $feature_cat_map_obj=array();
    foreach ($fea_obj as $v){
        $sig = $v["sig"];
        $fet_cat_name = $v["text"];
        $type = $v["type"];
        $parent = $v["parent"];
        $feature_name = $v["text"];
      //  $feature_cat_map_obj=array();
        $logfile->logfile_writeline("\n\n-----type is :::$type ::::::: --------\n\n");
        if($type == "cat" && $parent == $feature_cat_sig){
            $feature_cat_table_container = insertPanel($features_pg_sec,'',"");
            $feature_cat_table_container->setAttribute('id',"swfm_".$sig."_prop_tbl_container");
            //addAttribToElement($feature_cat_table_container, '{"class":"swfm_prop_tbl_container_styl"}');
            $logfile->logfile_writeline("feature $feature_name");

            $featureCatTable = new sense_table([
                //'id'=>'test_tbl',
                'widgetStyle'=>'fea_tbl_wdg_sty',
                'headingStyle'=>'heading_sty',
                'headingText' => 'Test Table Heading Text',
                'headingTextStyle' => 'heading-text-sty',
                'contentStyle' => 'fea_tbl_cont_sty'
                
            ]);
        $featureCatTable->setParent($feature_cat_table_container);
        $row_obj = $featureCatTable->addRow();
        $featureCatTable->setRowID($row_obj, "row_0");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $cell_obj = $featureCatTable->addCell("$feature_name", TRUE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $featureCatTable->setCurrCellID("cell_swm_th");
        $feature_cat_map_obj[$sig]=$featureCatTable;
       // $logfile->logfile_writeline("\n\n----------festure cat array init -------------\n\n");
        //$logfile->logfile_writeline(print_r($feature_cat_map_obj,true));
       // $logfile->logfile_writeline("\n\n----------sig $sig -------------\n\n");
        }else if($type=="feature"){
            $logfile->logfile_writeline("\n\n----------parent $parent -------------\n\n");
            //$logfile->logfile_writeline(print_r($feature_cat_map_obj,true));
            if(array_key_exists($parent,$feature_cat_map_obj)){
                $sense_cat_table=$feature_cat_map_obj[$parent];
               // $logfile->logfile_writeline("\n\n----------fetching cat table obj instance-------------\n\n");
                $row_obj = $sense_cat_table->addRow();
                $sense_cat_table->setRowID($row_obj, "row_".$sig);
                $secedFeatCatSig = sec_push_val_single_entry ("feat_sig_map", $sig);
                //addAttribToElement($row_obj, '{"class":""}');
                addAttribToElement($row_obj, '{"data-prop_id": '.$sig.' }');
    
                $cell_obj = $sense_cat_table->addCell($feature_name);
               // $sense_cat_table->setCurrCellID("cell".$secedFeatCatSig."_1");
                //addAttribToElement($cell_obj, '{"class":"generic_feature_name_style"}');
                //addAttribToElement($cell_obj, '{"data-prop_id": '.$sig.' }');
                $lower_sig = strtolower($sig);
                //echo "the lower sig is".$lower_sig."</br>";
                addDataToElement($cell_obj, '{"j":"'.$lower_sig.'"}');
                addDataToElement($cell_obj, '{"s":"'.$secedFeatCatSig.'"}');
            }
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

$dialog_div2=insertElement($layout_main_container, 'div', '{"class":"","id":"swfm_cat_div"}',"");
$input=insertElement($dialog_div2, 'input', '{"class":"","id":"cat_txt"}',"");
$button=insertElement($dialog_div2, 'button', '{"class":"","id":"add_button"}',"Add");



$upd_role_n_cat_dialog = insertPanel($dialog_parent, '{"id":"upd_role_swfm_cat_dialog", "class":"upd_role_n_cat_dialog_style", "title":" Update Role"}',"");
$upd_role_n_cat_parent_cont = insertElement($upd_role_n_cat_dialog,"div",'{"class":"upd_role_n_cat_parent_cont_style"}', "");
addAttribToElement($upd_role_n_cat_parent_cont, '{"id":"upd_role_n_cat_parent_cont"}');


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
echo $doc->saveHTML();

$logfile->logfile_close();

?>

