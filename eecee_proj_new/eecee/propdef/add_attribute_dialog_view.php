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

include $eecee_php_lib_path.'eecee_lib.php';
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

/*
$add_attrib_dialog = insertPanel($main_container, '{"id":"add_attrib_dialog", "class":"add_attrib_dialog_style", "title":" "}',"");
$add_attrib_dialog_cont = insertElement($add_attrib_dialog,"div",'{"class":"add_attrib_dialog_cont_style"}', "");
addAttribToElement($add_attrib_dialog_cont, '{"id":"add_attrib_dialog_cont"}');

$attrb_name_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_name_cont","class":"attrb_name_cont_style"}', "");
$attrb_name_span = insertElement($attrb_name_cont,"span",'{"id":"attrb_name_span","class":"attrb_name_span_style"}', "Attributes::");

$attrb_dropdown_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_dropdown_cont","class":"attrb_dropdown_cont_style"}', "");
$attrb_dropdown = insertElement($attrb_dropdown_cont,"select",'{"id":"attrb_dropdown","class":"attrb_dropdown_style"}', "");
$attrb_dropdown_blank_option = insertElement($attrb_dropdown,"option",'{"id":"attrb_dropdown_blank_option","class":"attrb_dropdown_blank_option_style"}', "Select Attribute");

$hidden_elem_cont = insertElement($add_attrib_dialog_cont,"input",'{"id":"hidden_elem_cont","class":"hidden_elem_cont_style", "type":"hidden"}', "");


$attributes_sql = "SELECT * FROM attributes";
$attributes_temp = $conn->prepare($attributes_sql);
$attributes_temp->execute();
$attributes_result = $attributes_temp->get_result();
$attributes_fetchall = $attributes_result->fetch_all(MYSQLI_ASSOC);
$attributes_fetchall_str = var_export($attributes_fetchall, true);
$logfile->logfile_writeline("attributes_fetchall_str=".$attributes_fetchall_str); 
*/

$op = $_POST["op"];
$logfile->logfile_writeline("the op is ::::: ".$op);

if($op == "f"){
    $logfile->logfile_writeline("the op is :: ".$op);
    $fea_attrib_id = $_POST["fea_attrib_id"];
    $attrib_id = $_POST["attrib_id"];

    $logfile->logfile_writeline("the fea_attrib_id is :: ".$fea_attrib_id);

    $fea_attrib_id_decoded = sec_get_map_val ("feature_attrib_list_tbl_sig_map", $fea_attrib_id);
    $logfile->logfile_writeline("the encoded fea_attrib_id is".$fea_attrib_id);
    $logfile->logfile_writeline("the decoded fea_attrib_id_decoded is".$fea_attrib_id_decoded);

    $logfile->logfile_writeline("the attrib_id is :: ".$attrib_id);

    $attrib_id_decoded = sec_get_map_val ("attrib_list_tbl_sig_map", $attrib_id);
    $logfile->logfile_writeline("the encoded attrib_id is".$attrib_id);
    $logfile->logfile_writeline("the decoded attrib_id_decoded is".$attrib_id_decoded);

    $unit_fea_attrib_sql = "SELECT * FROM unit_fea_attrib WHERE id = ?";
    $unit_fea_attrib_temp = $conn->prepare($unit_fea_attrib_sql);
    $unit_fea_attrib_temp->bind_param("i",$fea_attrib_id_decoded);
    $unit_fea_attrib_temp->execute();
    $unit_fea_attrib_result = $unit_fea_attrib_temp->get_result();
    $unit_fea_attrib_fetch_assoc = $unit_fea_attrib_result->fetch_assoc();
    $atrb_id = $unit_fea_attrib_fetch_assoc["attrib_id"];
    $atrb_val = $unit_fea_attrib_fetch_assoc["attrib_val"]; // the value that needs to be edited
    $logfile->logfile_writeline("the atrb_id is :::: ".$atrb_id);
    $logfile->logfile_writeline("the atrb_val is :::: ".$atrb_val);


    $attributes_sql = "SELECT * FROM attributes WHERE id = ?";
    $attributes_temp = $conn->prepare($attributes_sql);
    $attributes_temp->bind_param("i",$atrb_id);
    $attributes_temp->execute();
    $attributes_result = $attributes_temp->get_result();
    $attributes_fetch_assoc = $attributes_result->fetch_assoc();
    $attrib_name = $attributes_fetch_assoc["name"];
    $master_id = $attributes_fetch_assoc["master_id"];
    $logfile->logfile_writeline("the attrib_name is :::: ".$attrib_name);
    $logfile->logfile_writeline("the master_id is :::: ".$master_id);

    $add_attrib_dialog = insertPanel($main_container, '{"id":"add_attrib_dialog", "class":"add_attrib_dialog_style", "title":" "}',"");
    $add_attrib_dialog_cont = insertElement($add_attrib_dialog,"div",'{"class":"add_attrib_dialog_cont_style"}', "");
    addAttribToElement($add_attrib_dialog_cont, '{"id":"add_attrib_dialog_cont"}');

    
    $attrb_name_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_name_cont","class":"attrb_name_cont_style"}', $attrib_name);

    if($attrib_id_decoded == 11 || $attrib_id_decoded == 12 || $attrib_id_decoded == 9 || $attrib_id_decoded == 10){
        $attrb_val_txtbox_parent_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_val_txtbox_parent_cont","class":"attrb_val_txtbox_parent_cont_style"}', "");
        $attrb_val_txtbox_cont = insertElement($attrb_val_txtbox_parent_cont,"div",'{"id":"attrb_val_txtbox_cont","class":"attrb_val_txtbox_cont_style"}', "");
        $attrb_val_dd = insertElement($attrb_val_txtbox_cont,"select",'{"type":"text", "id":"attrb_val_dd","class":"attrb_val_dd_style"}', "");
        //$attrb_val_dd_blank_option = insertElement($attrb_val_dd,"option",'{"id":"attrb_val_dd_blank_option","class":"attrb_val_dd_blank_option_style", "value": "0"}', "Select Orientation");
        $mas_opt_sql = "SELECT * FROM eecee_master_options WHERE master_id = ?";
        $mas_opt_temp = $conn->prepare($mas_opt_sql);
        $mas_opt_temp->bind_param("i",$master_id);
        $mas_opt_temp->execute();
        $mas_opt_result = $mas_opt_temp->get_result();
        $mas_opt_fetch_all = $mas_opt_result->fetch_all(MYSQLI_ASSOC);
        //$mas_opt_fetch_all_str = var_export($mas_opt_fetch_all, true);
        //$logfile->logfile_writeline("op = f, mas_opt_fetch_all_str=".$mas_opt_fetch_all_str); 
        foreach ($mas_opt_fetch_all as $value){
            $mas_opt_id = $value["id"];
            $mas_opt_name = $value["name"];
            $seced_mas_opt_id = sec_push_val_single_entry ("mas_opt_id_map", $mas_opt_id);
            //$attrb_val_dd_option = insertElement($attrb_val_dd,"option",'{"id":"attrb_val_dd_option","class":"attrb_val_dd_option_style", "value": "'.$seced_mas_opt_id.'"}', $mas_opt_name);
            if($mas_opt_id == $atrb_val){
                $attrbval_dd_option = insertElement($attrb_val_dd,"option",'{"id":"attrb_val_dd_option","class":"attrb_val_dd_option_style", "selected":"selected", "value": "'.$seced_mas_opt_id.'"}', $mas_opt_name);
            }else{
                $attrbval_dd_option = insertElement($attrb_val_dd,"option",'{"id":"attrb_val_dd_option","class":"attrb_val_dd_option_style", "value": "'.$seced_mas_opt_id.'"}', $mas_opt_name);
            }
        }

    }else{
        $attrb_value_textbox_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_value_textbox_cont","class":"attrb_value_textbox_cont_style"}', "");
        $attrb_value_textbox = insertElement($attrb_value_textbox_cont,"input",'{"id":"attrb_value_textbox","class":"attrb_value_textbox_style", "value": "'.$atrb_val.'"}', "");
    }
    

    $attrb_update_btn_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_update_btn_cont","class":"attrb_update_btn_cont_style"}', "");
    $attrb_update_btn = insertElement($attrb_update_btn_cont,"span",'{"id":"attrb_update_btn","class":"attrb_update_btn_style"}', "Update");

    $attrb_delete_btn_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_delete_btn_cont","class":"attrb_delete_btn_cont_style"}', "");
    $attrb_delete_btn = insertElement($attrb_delete_btn_cont,"span",'{"id":"attrb_delete_btn","class":"attrb_delete_btn_style"}', "Delete");

    $del_attrib_cont = insertElement($add_attrib_dialog,"div",'{"class":"del_attrib_cont_style"}', "");
    addAttribToElement($del_attrib_cont, '{"id":"del_attrib_cont"}'); // delete div
    $del_message_cont = insertElement($del_attrib_cont,"div",'{"class":"del_message_cont_style"}', "Do you want to remove this attribute from this feature?");
    $del_yes_cont = insertElement($del_attrib_cont,"div",'{"id":"del_yes_cont","class":"del_yes_cont_style"}', "yes");
    $del_no_cont = insertElement($del_attrib_cont,"div",'{"id":"del_no_cont","class":"del_no_cont_style"}', "no");
}

else{

    $add_attrib_dialog = insertPanel($main_container, '{"id":"add_attrib_dialog", "class":"add_attrib_dialog_style", "title":" "}',"");
    $add_attrib_dialog_cont = insertElement($add_attrib_dialog,"div",'{"class":"add_attrib_dialog_cont_style"}', "");
    addAttribToElement($add_attrib_dialog_cont, '{"id":"add_attrib_dialog_cont"}');
    
    $attrb_name_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_name_cont","class":"attrb_name_cont_style"}', "");
    $attrb_name_span = insertElement($attrb_name_cont,"span",'{"id":"attrb_name_span","class":"attrb_name_span_style"}', "Attributes::");
    
    $attrb_dropdown_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_dropdown_cont","class":"attrb_dropdown_cont_style"}', "");
    $attrb_dropdown = insertElement($attrb_dropdown_cont,"select",'{"id":"attrb_dropdown","class":"attrb_dropdown_style"}', "");
    $attrb_dropdown_blank_option = insertElement($attrb_dropdown,"option",'{"id":"attrb_dropdown_blank_option","class":"attrb_dropdown_blank_option_style"}', "Select Attribute");
    
    $hidden_elem_cont = insertElement($add_attrib_dialog_cont,"input",'{"id":"hidden_elem_cont","class":"hidden_elem_cont_style", "type":"hidden"}', "");
    
    
    $attributes_sql = "SELECT * FROM attributes";
    $attributes_temp = $conn->prepare($attributes_sql);
    $attributes_temp->execute();
    $attributes_result = $attributes_temp->get_result();
    $attributes_fetchall = $attributes_result->fetch_all(MYSQLI_ASSOC);
    $attributes_fetchall_str = var_export($attributes_fetchall, true);
    $logfile->logfile_writeline("attributes_fetchall_str=".$attributes_fetchall_str); 


    if($op == "l"){
        sec_clear_map ("attrib_id_map");
        foreach ($attributes_fetchall as $value){
            $attrib_id = $value["id"];
            $attrib_name = $value["name"];
            //$logfile->logfile_writeline("the attrib_id is :: ".$attrib_id);
            $seced_attrib_id = sec_push_val_single_entry ("attrib_id_map", $attrib_id);
            //$logfile->logfile_writeline("the seced_attrib_id is :: ".$seced_attrib_id);
            $attrb_dropdown_option = insertElement($attrb_dropdown,"option",'{"id":"attrb_dropdown_option","class":"attrb_dropdown_option_style", "value": "'.$seced_attrib_id.'"}', $attrib_name);
        }

        /*
        $curr_map = sec_get_map("attrib_id_map");
        
        $logfile->logfile_writeline(__FILE__."---Dumping attrib_id_map MAP:: op==l Begin");
                foreach($curr_map as $key => $value)
                    {
                        $logfile->logfile_writeline($key." : ".$value);
                    }
        $logfile->logfile_writeline(__FILE__."---Dumping attrib_id_map MAP: End");
        */
    }
    else if($op == "d"){
        $post_attrib_id = $_POST["attrib_id"];
        $post_attrib_id_decoded = sec_get_map_val ("attrib_id_map", $post_attrib_id);

        $curr_map = sec_get_map("attrib_id_map");
        
        $logfile->logfile_writeline(__FILE__."---Dumping attrib_id_map MAP:: op==d  Begin");
                foreach($curr_map as $key => $value)
                    {
                        $logfile->logfile_writeline($key." : ".$value);
                    }
        $logfile->logfile_writeline(__FILE__."---Dumping attrib_id_map MAP: End");

        $logfile->logfile_writeline("the encoded attribute ID is".$post_attrib_id);
        $logfile->logfile_writeline("the decoded attribute ID is".$post_attrib_id_decoded);

        $attributes_sql = "SELECT * FROM attributes WHERE id = ?";
        $attributes_temp = $conn->prepare($attributes_sql);
        $attributes_temp->bind_param("i",$post_attrib_id_decoded);
        $attributes_temp->execute();
        $attributes_result = $attributes_temp->get_result();
        $attributes_fetch_assoc = $attributes_result->fetch_assoc();

        $attributes_fetch_assoc_str = var_export($attributes_fetch_assoc, true);
        $logfile->logfile_writeline("attributes_fetch_assoc_str =".$attributes_fetch_assoc_str); 

        $attrib_type = $attributes_fetch_assoc["type"];
        $logfile->logfile_writeline("the attrib_type is".$attrib_type);

        foreach ($attributes_fetchall as $value){
            $attrib_id = $value["id"];
            $attrib_name = $value["name"];
            $seced_attrib_id = sec_push_val_single_entry ("attrib_id_map", $attrib_id);

            $logfile->logfile_writeline("the post_attrib_id_decoded is".$post_attrib_id_decoded);
            if($attrib_id == $post_attrib_id_decoded){
                $attrb_dropdown_option = insertElement($attrb_dropdown,"option",'{"id":"attrb_dropdown_option","class":"attrb_dropdown_option_style", "selected":"selected", "value": "'.$seced_attrib_id.'"}', $attrib_name);
            }else{
                $logfile->logfile_writeline("the post_attrib_id_decoded is".$post_attrib_id_decoded);
                $attrb_dropdown_option = insertElement($attrb_dropdown,"option",'{"id":"attrb_dropdown_option","class":"attrb_dropdown_option_style", "value": "'.$seced_attrib_id.'"}', $attrib_name);
            }
        }
        //addAttribToElement($attrb_dropdown_option, "{'': $unit_type_name }");

        if($attrib_type == "rank" || $attrib_type == "choice"){
            $attrb_val_txtbox_parent_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_val_txtbox_parent_cont","class":"attrb_val_txtbox_parent_cont_style"}', "");

            //$attrb_val_txtbox_type_name = insertElement($attrb_val_txtbox_parent_cont,"div",'{"id":"attrb_val_txtbox_type_name","class":"attrb_val_txtbox_type_name_style"}', $attrib_type);

            $attrb_val_txtbox_cont = insertElement($attrb_val_txtbox_parent_cont,"div",'{"id":"attrb_val_txtbox_cont","class":"attrb_val_txtbox_cont_style"}', "");
            $attrb_val_dd = insertElement($attrb_val_txtbox_cont,"select",'{"type":"text", "id":"attrb_val_dd","class":"attrb_val_dd_style"}', "");
            $attrb_val_dd_blank_option = insertElement($attrb_val_dd,"option",'{"id":"attrb_val_dd_blank_option","class":"attrb_val_dd_blank_option_style", "value": "0"}', "Select Option");


            $master_id = $attributes_fetch_assoc["master_id"];
            $logfile->logfile_writeline("the master_id is ::: ".$master_id);

            $mas_opt_sql = "SELECT * FROM eecee_master_options WHERE master_id = ?";
            $mas_opt_temp = $conn->prepare($mas_opt_sql);
            $mas_opt_temp->bind_param("i",$master_id);
            $mas_opt_temp->execute();
            $mas_opt_result = $mas_opt_temp->get_result();
            $mas_opt_fetch_all = $mas_opt_result->fetch_all(MYSQLI_ASSOC);

            $mas_opt_fetch_all_str = var_export($mas_opt_fetch_all, true);
            $logfile->logfile_writeline("mas_opt_fetch_all_str=".$mas_opt_fetch_all_str);
            
            foreach ($mas_opt_fetch_all as $value){
                $mas_opt_id = $value["id"];
                $mas_opt_name = $value["name"];
                $seced_mas_opt_id = sec_push_val_single_entry ("mas_opt_id_map", $mas_opt_id);
                $attrb_val_dd_option = insertElement($attrb_val_dd,"option",'{"id":"attrb_val_dd_option","class":"attrb_val_dd_option_style", "value": "'.$seced_mas_opt_id.'"}', $mas_opt_name);
            }

        }else{
            $attrb_val_txtbox_parent_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"attrb_val_txtbox_parent_cont","class":"attrb_val_txtbox_parent_cont_style"}', "");

            $attrb_val_txtbox_type_name = insertElement($attrb_val_txtbox_parent_cont,"div",'{"id":"attrb_val_txtbox_type_name","class":"attrb_val_txtbox_type_name_style"}', $attrib_type);

            $attrb_val_txtbox_cont = insertElement($attrb_val_txtbox_parent_cont,"div",'{"id":"attrb_val_txtbox_cont","class":"attrb_val_txtbox_cont_style"}', "");
            $attrb_val_txtbox = insertElement($attrb_val_txtbox_cont,"input",'{"type":"text", "id":"attrb_val_txtbox","class":"attrb_val_txtbox_style"}', "");

            if($attrib_type == "len"){
                $attrb_val_txtbox_type_name = insertElement($attrb_val_txtbox_parent_cont,"div",'{"id":"attrb_val_txtbox_type_name","class":"attrb_val_txtbox_type_name_style"}', "Mt.");
            }else if($attrib_type == "area"){
                $attrb_val_txtbox_type_name = insertElement($attrb_val_txtbox_parent_cont,"div",'{"id":"attrb_val_txtbox_type_name","class":"attrb_val_txtbox_type_name_style"}', "Sq. Mt.");
            }
            
        }

        $add_attrb_btn_cont = insertElement($add_attrib_dialog_cont,"div",'{"id":"add_attrb_btn_cont","class":"add_attrb_btn_cont_style"}', "");
        $add_feat_btn_span = insertElement($add_attrb_btn_cont,"span",'{"id":"add_feat_btn_span","class":"add_feat_btn_span_style"}', "Add This Attribute");
    }
    else if($op == "c"){ // insert into unit_fea_attrib table
        $feat_id = $_POST["feat_id"];
        $feat_id_decoded = sec_get_map_val ("feat_list_tbl_sig_map", $feat_id);
        $logfile->logfile_writeline("the encoded feature ID is:: ".$feat_id);
        $logfile->logfile_writeline("the decoded feature ID is:: ".$feat_id_decoded);

        //$attrib_id = $_POST["attrib_id"];
        $attrib_id = $_POST["attrib_id"];

        $attrib_id_decoded = sec_get_map_val ("attrib_id_map", $attrib_id);
        $logfile->logfile_writeline("the encoded attribute ID is".$attrib_id);
        $logfile->logfile_writeline("the decoded attribute ID is".$attrib_id_decoded);

        $unit_type_id = $SENSESSION->get_val("unit_type_id");
        $logfile->logfile_writeline("the unit_type ID is".$unit_type_id);

        if($attrib_id_decoded == "11" || $attrib_id_decoded == "12" || $attrib_id_decoded == "9" || $attrib_id_decoded == "10"){
            $mas_opt_val = $_POST["mas_opt_val"];
            $mas_opt_val_decoded = sec_get_map_val ("mas_opt_id_map", $mas_opt_val);
            $logfile->logfile_writeline("the mas_opt_val is".$mas_opt_val);
            $logfile->logfile_writeline("the mas_opt_val_decoded is".$mas_opt_val_decoded);
            $attrib_value = $mas_opt_val_decoded;
        }else{
            $attrib_value = $_POST["attrib_value"];
            
        }
        

        

        $sql_insrt="INSERT INTO unit_fea_attrib(unit_fea_id, attrib_id, unit_type_id, attrib_val) VALUES (?, ?, ?, ?)";
            $sql_temp = $conn->prepare($sql_insrt);
            if($sql_temp){
                $logfile->logfile_writeline("prepare successful");
                $bind_temp = $sql_temp->bind_param("iiid", $feat_id_decoded, $attrib_id_decoded, $unit_type_id, $attrib_value);
                if($bind_temp){
                    $logfile->logfile_writeline("bind successful");
                    $exe_temp = $sql_temp->execute();
                    $exe_error = $sql_temp->error;
                    $logfile->logfile_writeline("execution exe_error is :: ".$exe_error);
                    if($exe_temp){
                    $logfile->logfile_writeline("execution successful");
                    addAttribToElement($hidden_elem_cont, '{"value":"0"}');
                    $logfile->logfile_writeline(print_r($hidden_elem_cont, true));

                    }else{
                        $logfile->logfile_writeline("execution failed");
                        addAttribToElement($hidden_elem_cont, '{"value":"1"}');
                    $logfile->logfile_writeline(print_r($hidden_elem_cont, true));
                    }
                }else{
                    //echo "bind failed \n";
                    $logfile->logfile_writeline("bind failed");
                }
            }else{
            // echo "prepare failed \n";
            $logfile->logfile_writeline("prepare failed");
            }
    }
    else if($op == "w"){
        $logfile->logfile_writeline("the op is: ".$op);
        $fea_attrib_id = $_POST["fea_attrib_id"];
        $logfile->logfile_writeline("the fea_attrib_id is: ".$fea_attrib_id);
        
        $upd_val = $_POST["upd_val"];
        $logfile->logfile_writeline("the upd_val is: ".$upd_val);

        $attrib_id = $_POST["attrib_id"];
        $logfile->logfile_writeline("the attrib_id is: ".$attrib_id);

        $attrib_id_decoded = sec_get_map_val ("attrib_list_tbl_sig_map", $attrib_id);
        $logfile->logfile_writeline("the encoded attribute ID is".$attrib_id);
        $logfile->logfile_writeline("the decoded attribute ID is".$attrib_id_decoded);

        
        if($attrib_id_decoded == 11 || $attrib_id_decoded == 12 || $attrib_id_decoded == 9 || $attrib_id_decoded == 10){
            $master_upd_val = $_POST["upd_val"];
            $master_upd_val_decoded = sec_get_map_val ("mas_opt_id_map", $master_upd_val);
            $upd_val = $master_upd_val_decoded;
        }else{
            $upd_val = $_POST["upd_val"];
        }

        $fea_attrib_id_decoded = sec_get_map_val ("feature_attrib_list_tbl_sig_map", $fea_attrib_id);
        $logfile->logfile_writeline("the encoded fea_attrib_id is".$fea_attrib_id);
        $logfile->logfile_writeline("the decoded fea_attrib_id_decoded is".$fea_attrib_id_decoded);
        
        
        $unit_fea_attrib_sql = "UPDATE unit_fea_attrib SET attrib_val=? WHERE id=?";
        $upd_stmt=$conn->prepare($unit_fea_attrib_sql);
        $upd_stmt->bind_param("si", $upd_val, $fea_attrib_id_decoded);
        $upd_stmt->execute();
        if($upd_stmt){
            $logfile->logfile_writeline("the execution is successful");
            addAttribToElement($hidden_elem_cont, '{"value":"0"}');
        }
        
        
    }
    else if($op == "del"){
        $logfile->logfile_writeline("the op is: ".$op);
        $fea_attrib_id = $_POST["fea_attrib_id"];
        //$logfile->logfile_writeline("the fea_attrib_id is: ".$fea_attrib_id);
        //$upd_val = $_POST["upd_val"];
        //$logfile->logfile_writeline("the upd_val is: ".$upd_val);
        //$attrib_id = $_POST["attrib_id"];

        $fea_attrib_id_decoded = sec_get_map_val ("feature_attrib_list_tbl_sig_map", $fea_attrib_id);
        $logfile->logfile_writeline("the encoded fea_attrib_id is".$fea_attrib_id);
        $logfile->logfile_writeline("the decoded fea_attrib_id_decoded is".$fea_attrib_id_decoded);
        
        /*
        $unit_fea_attrib_sql = "UPDATE unit_fea_attrib SET attrib_val=? WHERE id=?";
        $upd_stmt=$conn->prepare($unit_fea_attrib_sql);
        $upd_stmt->bind_param("si", $upd_val, $fea_attrib_id_decoded);
        $upd_stmt->execute();
        if($upd_stmt){
            $logfile->logfile_writeline("the execution is successful");
            addAttribToElement($hidden_elem_cont, '{"value":"0"}');
        }
        */

        $sql_delete = $conn->prepare("DELETE from unit_fea_attrib where id = ?");
        $sql_delete->bind_param("i", $fea_attrib_id_decoded);
        $sql_delete->execute();
        $sql_delete_res = $sql_delete->get_result();
        if($sql_delete){
            $logfile->logfile_writeline("the delete from unit_fea_attrib is successful");
            addAttribToElement($hidden_elem_cont, '{"value":"0"}');
        }
        
        
    }
}






//$unit_type_edit = insertElement($main_container,"div",'{"id":"unit_type_edit", "class":"unit_type_edit_style"}'," ");
//$unit_type_edit_pg_sec = insertPageSection  ($unit_type_edit, '', '{"class":"container_body_pg_sec_style"}'); // the page section element



echo $doc->saveHTML();

$logfile->logfile_close();


?>