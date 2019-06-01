<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'sense_lib.php';
include $sense_common_php_lib_path.'session_exp.php';
include $eecee_php_lib_path.'eecee_lib.php';
include $eecee_php_lib_path.'eecee_sec_map.php';
include $sense_common_php_lib_path.'actl_lib.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
$html.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
$html.="<html>";
$html.="<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">";
$html.="<body>";
$html.="    <div id=\"main_cont\">";
$html.="    </div>";
$html.="</body>";
$html.="</html>";


$doc = createDomDoc($html);
$main_container =$doc->getElementById('main_cont');

$op = $_POST["op"];
$logfile->logfile_writeline("the op is :: ".$op);


$feat_list = insertElement($main_container,"div",'{"id":"unit_type_list", "class":"unit_type_list_style"}'," ");
$feat_list_pg_sec = insertPageSection  ($feat_list, '', '{"class":"container_body_pg_sec_style"}');

//$create_btn_cont = insertElement($feat_list_pg_sec,"div",'{"id":"create_btn_cont", "class":"create_btn_cont_style"}'," ");
//$create_btn_span = insertElement($create_btn_cont,"span",'{"id":"create_btn_span", "class":"create_btn_span_style"}',"Create New Unit Type");

$feat_table_container = insertPanel($feat_list_pg_sec,'',"");
$feat_table_container->setAttribute('id',"setup_prop_tbl_container");

//$feat_table = new sense_table("test_tbl");
$feat_table = new sense_table([
                                //'id'=>'test_tbl',
                                'widgetStyle'=>'wdg_sty',
                                'headingStyle'=>'heading_sty',
                                'headingText' => 'Test Table Heading Text',
                                'headingTextStyle' => 'heading-text-sty',
                                'contentTableStyle' => 'unit_type_feat_tbl_sty',
                                'contentStyle' => 'unit_type_feat_tbl_sty'
]);


//$feat_table->setAttrib('{"class":"generic_table"}');
sec_clear_map ("feat_list_tbl_sig_map");
$the_table = $feat_table->setParent($feat_table_container);

$row_obj = $feat_table->addRow();
$feat_table->setRowID($row_obj, "row_0");
addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
$cell_obj = $feat_table->addCell("", TRUE);
$feat_table->setCurrCellID("cell_1");

//$feature_heading_parent_cont = insertElement($cell_obj,"div",'{"id":"feature_heading_parent_cont", "class":"feature_heading_parent_cont_style"}'," ");
$feature_heading = insertElement($cell_obj,"div",'{"id":"feature_heading", "class":"feature_heading_style"}',"");
$add_feature_name_plus = insertElement($feature_heading,"span",'{"id":"add_feature_name_plus", "class":"add_feature_name_plus_style"}',"Features");

$add_feature_plus_cont = insertElement($cell_obj,"div",'{"id":"add_unit_feature_plus_cont", "class":"add_feature_plus_cont_style"}'," ");
//$add_feature_plus = insertElement($cell_obj,"span",'{"id":"add_feature_plus", "class":"add_feature_plus_style"}',"");
$add_feature_plus = insertElement($add_feature_plus_cont,"img",'{"id":"add_feature_plus", "src":"../themes/images/plus_1_16x16.png"}',"");
//src=\"../themes/images/userlogo.png\"

$cell_obj = $feat_table->addCell("", TRUE);
$feat_table->setCurrCellID("cell_2");

$unit_type_id = $SENSESSION->get_val("unit_type_id");
$logfile->logfile_writeline("The unit_type_id is :: ".$unit_type_id);

$prop_id = $SENSESSION->get_val("prop_id");
$logfile->logfile_writeline("The prop_id is :: ".$prop_id);

$unit_types_sql = "SELECT * FROM unit_type_fea WHERE prop_id = ? and unit_type_id = ?";
$unit_types_temp = $conn->prepare($unit_types_sql);
$unit_types_temp->bind_param("ii",$prop_id, $unit_type_id);
$unit_types_temp->execute();
$unit_types_result = $unit_types_temp->get_result();
$unit_types_fetch_all = $unit_types_result->fetch_all(MYSQLI_ASSOC);

$unit_types_fetch_all_str = var_export($unit_types_fetch_all, true);
$logfile->logfile_writeline("The unit_types_fetch_all_str is :: ".$unit_types_fetch_all_str);



foreach ($unit_types_fetch_all as $v){
    $unit_fea_id = $v["unit_fea_id"];
    $unit_features_sql = "SELECT * FROM unit_features where id = ?";
    $unit_features_temp = $conn->prepare($unit_features_sql);
    $unit_features_temp->bind_param("i",$unit_fea_id);
    $unit_features_temp->execute();
    $unit_features_result = $unit_features_temp->get_result();
    $unit_features_result_fetch_as = $unit_features_result->fetch_assoc();

    $unit_features_result_fetch_as_str = var_export($unit_features_result_fetch_as, true);
    $logfile->logfile_writeline("The unit_features_result_fetch_as_str is :: ".$unit_features_result_fetch_as_str);
    $feat_name = $unit_features_result_fetch_as["name"];
    $feat_id = $unit_features_result_fetch_as["id"];
    $logfile->logfile_writeline("The feat_id is :: ".$feat_id);

    $secedFeatId = sec_push_val_single_entry ("feat_list_tbl_sig_map", $feat_id);

    /*
    $curr_map = sec_get_map("feat_list_tbl_sig_map");
    
    $logfile->logfile_writeline(__FILE__."---Dumping feat_list_tbl_sig_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."---Dumping feat_list_tbl_sig_map MAP: End");
    */

    $logfile->logfile_writeline("The seced feat_id is :: ".$secedFeatId);

    $row_obj = $feat_table->addRow();
    $feat_table->setRowID($row_obj, "row_".$feat_id);
    addAttribToElement($row_obj, '{"class":""}');
    addAttribToElement($row_obj, '{"data-prop_id": '.$feat_id.' }');

    // first column starts
    $cell_obj = $feat_table->addCell("");
    $feat_table->setCurrCellID("cell".$secedFeatId."_1");
    addAttribToElement($cell_obj, '{"class":"Feature_list_name_style"}');

    //$feature_name_container = insertElement($cell_obj,"div",'{"id": "feature_name_container", "class":"feature_name_container_style"}'," "); 
    $attrib_acc_container = insertElement($cell_obj,"div","{\"id\": \"feature_name_container_\".$secedFeatId, \"class\":\"feature_name_container_style\"}"," "); // feature name (where the accordion is to be inserted)
    //$feature_name_span = insertElement($feature_name_container,"span",'{"id": "feature_name_span", "class":"feature_name_span_style"}',$feat_name); 

    $attrib_acc_accordion = new jqueryui_accordion_widget("attrib_accordion_".$feat_id);
    //$attrib_acc_accordion->addClass("genereic_accordion");
    $attrib_acc_accordion->addClass("attrib_accordion_style");
    $attrib_acc_accordion->setParent($attrib_acc_container);

    
    $attrib_acc_accordion->insertTab("attribs_".$feat_id,$feat_name);
    
    $attrib_accordion_view = $attrib_acc_accordion->addViewtoTabContainer("attribs_".$feat_id,"attrib_accordion_view_".$feat_id);
    $attrib_accordion_view_ps = insertPageSection  ($attrib_accordion_view, '', '{"class":"attrib-accordion-page-section"}');

    
    $action_grand_parent_div = insertElement($attrib_accordion_view_ps,"div",'{"id": "action_grand_parent_div", "class":"action_grand_parent_div_style"}'," "); 
    

    /* add attribute + button :: start
    $left_plus_btn_cont = insertElement($action_grand_parent_div,"div",'{"id": "left_plus_btn_cont", "class":"left_plus_btn_cont_style"}',""); 
    addDataToElement($left_plus_btn_cont, '{"ut":"'.$secedFeatId.'"}');
    $left_plus_btn_span = insertElement($left_plus_btn_cont,"span",'{"id": "left_plus_btn_span", "class":"left_plus_btn_span_style"}',"+"); 
    */ //add attribute + button :: end

    $actions_tbl_cont = insertElement($action_grand_parent_div,"div",'{"id": "actions_tbl_cont", "class":"actions_tbl_cont_style"}',""); 

    //$left_plus_btn_cont = insertElement($action_grand_parent_div,"div",'{"id": "left_plus_btn_cont", "class":"left_plus_btn_cont_style"}',""); 
    //addDataToElement($left_plus_btn_cont, '{"ut":"'.$secedFeatId.'"}');
    //$left_plus_btn_span = insertElement($left_plus_btn_cont,"span",'{"id": "left_plus_btn_span", "class":"left_plus_btn_span_style"}',"+"); 

    $feat_action_table_container = insertPanel($actions_tbl_cont,'',"");
    $feat_action_table_container->setAttribute('id',"feat_action_table_container_".$secedFeatId);

    

    // feature atribute table :: start

    $feat_action_table = new sense_table([
        //'id'=>'test_tbl',
        'widgetStyle'=>'attrib_tbl_wdg_sty',
        'headingStyle'=>'heading_sty',
        'headingText' => 'Test Table Heading Text',
        'headingTextStyle' => 'heading-text-sty',
        'contentTableStyle' => 'attrib_tbl_sty',
        'contentStyle' => 'attrib_tbl_cont_sty'
    ]);

    $feat_action_table->setParent($feat_action_table_container);

    $row_obj = $feat_action_table->addRow();
    $feat_action_table->setRowID($row_obj, "row_0");
    //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
    $cell_obj = $feat_action_table->addCell("", TRUE);
    $feat_action_table->setCurrCellID("act_cell_1");

    //$attrib_heading_grand_parent_cont = insertElement($cell_obj,"div",'{"id": "attrib_heading_grand_parent_cont", "class":"attrib_heading_grand_parent_cont_style"}',"");
    $attrib_heading_parent_cont = insertElement($cell_obj,"div",'{"id": "attrib_heading_parent_cont", "class":"attrib_heading_parent_cont_style"}',"");    

    $attrib_heading_cont = insertElement($attrib_heading_parent_cont,"div",'{"id": "attrib_heading_cont", "class":"attrib_heading_cont_style"}',""); 
    addDataToElement($attrib_heading_cont, '{"ut":"'.$secedFeatId.'"}');
    $attrib_heading_span = insertElement($attrib_heading_cont,"span",'{"id": "attrib_heading_span", "class":"attrib_heading_span_style"}',"Attributes"); 

    $add_atr_btn_cont = insertElement($attrib_heading_parent_cont,"div",'{"id": "add_atr_btn_cont", "class":"add_atr_btn_cont_style"}',""); 
    addDataToElement($add_atr_btn_cont, '{"ut":"'.$secedFeatId.'"}');
    $add_atr_btn_span = insertElement($add_atr_btn_cont,"img",'{"id": "left_plus_btn_span", "class":"left_plus_btn_span_style" , "src": "../themes/images/plus_1_16x16.png"}',"+"); 

    /*
    $cell_obj2 = $feat_action_table->addCell("", TRUE);
    $feat_action_table->setCurrCellID("act_cell_2");

    $cell_obj3 = $feat_action_table->addCell("", TRUE);
    $feat_action_table->setCurrCellID("act_cell_3");
    */

    $unit_attrib_sql = "SELECT * FROM unit_fea_attrib where unit_fea_id = ? and unit_type_id = ?";
    $unit_attrib_temp = $conn->prepare($unit_attrib_sql);
    $unit_attrib_temp->bind_param("ii",$feat_id, $unit_type_id);
    $unit_attrib_temp->execute();
    $unit_attrib_result = $unit_attrib_temp->get_result();
    $unit_attrib_fetch_al = $unit_attrib_result->fetch_all(MYSQLI_ASSOC);

    $unit_attrib_fetch_al_str = var_export($unit_attrib_fetch_al, true);
    $logfile->logfile_writeline("The unit_attrib_fetch_al_str is ::: ".$unit_attrib_fetch_al_str);

    foreach ($unit_attrib_fetch_al as $k=>$v){
        $attrib_id = $v["attrib_id"];
        $logfile->logfile_writeline("The attrib_id is ::: ".$attrib_id);
        if($attrib_id == 11 || $attrib_id == 12 || $attrib_id == 9 || $attrib_id == 10){
            $logfile->logfile_writeline("getting inside this ::");
            $mas_opt_id  = $v["attrib_val"];
            $logfile->logfile_writeline("The mas_opt_id is ::: ".$mas_opt_id);
            $sql_stmt = "SELECT * FROM eecee_master_options where id = ?";
            $sql_temp = $conn->prepare($sql_stmt);
            $sql_temp->bind_param("i",$mas_opt_id);
            $sql_temp->execute();
            $sql_result = $sql_temp->get_result();
            $sql_fetch_as = $sql_result->fetch_assoc();
            $sql_fetch_as_str = var_export($sql_fetch_as, true);
            $logfile->logfile_writeline("The sql_fetch_as_str is ::: ".$sql_fetch_as_str);
            $attrib_val = $sql_fetch_as["name"];
        }else{
            $attrib_val = $v["attrib_val"];
        }
        
        $unit_fea_attrib_tbl_id = $v["id"];
        $logfile->logfile_writeline("The attrib_id is ::: ".$attrib_id);
        

        $unit_attrib_sql = "SELECT * FROM attributes where id = ?";
        $unit_attrib_temp = $conn->prepare($unit_attrib_sql);
        $unit_attrib_temp->bind_param("i",$attrib_id);
        $unit_attrib_temp->execute();
        $unit_attrib_result = $unit_attrib_temp->get_result();
        $unit_attrib_fetch_as = $unit_attrib_result->fetch_assoc();

        $seced_attrib_id = sec_push_val_single_entry ("attrib_list_tbl_sig_map", $attrib_id);
        $seced_fea_attrib_id = sec_push_val_single_entry ("feature_attrib_list_tbl_sig_map", $unit_fea_attrib_tbl_id);

        $logfile->logfile_writeline("The unseced_fea_attrib_id is ::: ".$unit_fea_attrib_tbl_id);
        $logfile->logfile_writeline("The seced_fea_attrib_id is ::: ".$seced_fea_attrib_id);

        $attrib_name = $unit_attrib_fetch_as["name"];
        $logfile->logfile_writeline("The attrib_name is ::: ".$attrib_name);

        $attrib_type = $unit_attrib_fetch_as["type"];
        $logfile->logfile_writeline("The attrib_type is ::: ".$attrib_type);

        $type_unit = type_unit_creator($attrib_type);
        $logfile->logfile_writeline("The type_unit is ::: ".$type_unit);

        $row_obj = $feat_action_table->addRow();
        $feat_action_table->setRowID($row_obj, "row_".$seced_attrib_id);
        addAttribToElement($row_obj, '{"class":"active_name_style"}');
        addDataToElement($row_obj, '{"att":"'.$seced_attrib_id.'"}');
        addDataToElement($row_obj, '{"f_att":"'.$seced_fea_attrib_id.'"}');
        
        $cell_obj = $feat_action_table->addCell($attrib_name." : ".$attrib_val." ".$type_unit);
        $feat_action_table->setCurrCellID("cell".$seced_attrib_id."_1");
        //addAttribToElement($cell_obj, '{"class":"active_action_style"}');
        addDataToElement($cell_obj, '{"att":"'.$seced_attrib_id.'"}');
        addDataToElement($cell_obj, '{"f_att":"'.$seced_fea_attrib_id.'"}');

        
    
    }
    // feature attribute table :: end 

    //addAttribToElement($cell_obj, '{"data-prop_id": '.$sig.' }');
    $lower_sig = strtolower($sig);
    //echo "the lower sig is".$lower_sig."</br>";
    addDataToElement($cell_obj, '{"j":"'.$lower_sig.'"}');
    addDataToElement($cell_obj, '{"s":"'.$secedFeatId.'"}');
    

    $cell_obj = $feat_table->addCell("");
    $feat_table->setCurrCellID("cell".$secedFeatId."_2");
    addAttribToElement($cell_obj, '{"class":"delete_feature_style"}');
    addDataToElement($cell_obj, '{"ut":"'.$secedFeatId.'"}');
    $del_feat_btn = insertElement($cell_obj,"img",'{"id": "del_feat_btn", "class":"del_feat_btn_style" , "src": "../themes/images/red_24.png"}',""); 
    addAttribToElement($del_feat_btn, '{"width":"16px", "height":"16px", "vertical-align":"middle"}');
    
}

echo $doc->saveHTML();

$logfile->logfile_close();


?>