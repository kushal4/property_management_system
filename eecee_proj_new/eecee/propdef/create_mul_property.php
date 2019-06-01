<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
//include '../prop_topo.php';
$log_path = $eecee_log_path."prop_def.log";
include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

$prop_id = $SENSESSION->get_val("prop_id");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$raw_json["ret_code"] = 0;
$raw_json["ret_code1"] = 0;
$raw_json["ret_code2"] = 0;
$raw_json["ret_code3"] = 0;
$raw_json["ret_code4"] = 0;

function validate_alphabet($textbox_val, $log){
    $ret_json["ret_code"] = 0;
    $ret_json["ret_message"] = "";
    $log->logfile_writeline("getting inside validate_alphabet function");
    $log->logfile_writeline(is_numeric($textbox_val));
    $check_num = is_numeric($textbox_val);
    if ($check_num == true) {
        $ret_json["ret_code"] = 1;
        $ret_json["ret_message"] = "(Must be letter)";
        //$ret_json_encoce=json_encode($ret_json);
        $log->logfile_writeline("must be a letter*****");
    }else{
        $textbox_val_length = strlen($textbox_val);
        if ($textbox_val_length != 1) {
            $ret_json["ret_code"] = 2;
            $ret_json["ret_message"] = "(Must be a single letter)";
            //$ret_json_encoce=json_encode($ret_json);
            $log->logfile_writeline("Must be a single letter");
        }
    }
    return $ret_json;

}

function validate_number($textbox_val, $log){
    $ret_json["ret_code"] = 0;
    $ret_json["ret_message"] = "";
    $log->logfile_writeline("getting inside validate_number function");
    $log->logfile_writeline(is_numeric($textbox_val));
    $check_num = is_numeric($textbox_val);
    if ($check_num == false) {
        $ret_json["ret_code"] = 3;
        $ret_json["ret_message"] = "(Must be a number)";
        //$ret_json_encoce=json_encode($ret_json);
        $log->logfile_writeline("must be a letter*****");
    }else{
        $textbox_val_length = strlen($textbox_val);
        if ($textbox_val_length > 3) {
            $ret_json["ret_code"] = 4;
            $ret_json["ret_message"] = "(3 digits Max)";
            //$ret_json_encoce=json_encode($ret_json);
            $log->logfile_writeline("3 digits Max");
        }
    }
    return $ret_json;
}

function validate_fixed($textbox_val, $log){
    $ret_json["ret_code"] = 0;
    $ret_json["ret_message"] = "";
    $log->logfile_writeline("getting inside validate_fixed");
    //$pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
    $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
        //$result = var_dump(preg_match($pattern, $textbox_val));
        //$log->logfile_writeline("the result is::".$result);
        //$log->logfile_writeline("the textbox value is".$textbox_val);
    if (preg_match($pattern, $textbox_val))
    {
            //$log->logfile_writeline("special characters are not allowed");
            $ret_json["ret_code"] = 5;
            $ret_json["ret_message"] = "(special characters not allowed)";
            //$ret_json_encoce=json_encode($ret_json);
            $log->logfile_writeline("special characters not allowed");
    }
    return $ret_json;
}

function validate_empty($textbox_val, $log){
    $ret_json["ret_code"] = 0;
    $ret_json["ret_message"] = "";
    return $ret_json;
}

function validate_name_format($log, $dd_type, $textbox_val){
    if($dd_type == "alphabetical order"){
        $dd_validate_result = validate_alphabet($textbox_val, $log);
    }else if($dd_type == "numeric order"){
        $dd_validate_result = validate_number($textbox_val, $log);
    }else if($dd_type == "fixed"){
        $dd_validate_result = validate_fixed($textbox_val, $log);
    }else if($dd_type == NULL){
        $dd_validate_result = validate_empty($textbox_val, $log);
    }
    return $dd_validate_result;
}

$logfile->logfile_writeline("getting inside create_mul_prop PHP");

if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $rad_sel = "";
    $prop_num = "";
    $node_id = "";
    $dd1_type = "";
    $textbox_val1 = "";
    $dd2_type = "";
    $textbox_val2 = "";
    $dd3_type = "";
    $textbox_val3 = "";
    $dd4_type = "";
    $textbox_val4 = "";

    foreach ($json_decoded as $key => $value) {
        
        if ($key=="rad_sel"){
            $rad_sel = $value;
        }
        if ($key=="prop_num"){
            $prop_num = $value;
        }
        if ($key=="node_id"){
            $node_id = $value;
        }
        if ($key=="textbox_val1"){
            $textbox_val1 = $value;
        }
        if ($key=="textbox_val2"){
            $textbox_val2 = $value;
        }
        if ($key=="textbox_val3"){
            $textbox_val3 = $value;
        }
        if ($key=="textbox_val4"){
            $textbox_val4 = $value;
        }

        if ($key=="dd1_sel"){
            $dd1_type = $value;
        }
        if ($key=="dd2_sel"){
            $dd2_type = $value;
        }
        if ($key=="dd3_sel"){
            $dd3_type = $value;
        }
        if ($key=="dd4_sel"){
            $dd4_type = $value;
        }
    }
    //echo $node_id;
    $logfile->logfile_writeline("the rad sel is".$rad_sel);
    $logfile->logfile_writeline("the encoded node ID is".$node_id);
    $logfile->logfile_writeline("the encoded node ID is".$node_id);

    $node_id_mapped = sec_get_map_val ("prop_topo_map", $node_id);
    $logfile->logfile_writeline("the encoded node ID is".$node_id);
    $logfile->logfile_writeline("the decoded node ID is".$node_id_mapped);

    $logfile->logfile_writeline("the value of the textbox1 is: ".$textbox_val1);
    $logfile->logfile_writeline("the value of the textbox2 is: ".$textbox_val2);
    $logfile->logfile_writeline("the value of the textbox3 is: ".$textbox_val3);
    $logfile->logfile_writeline("the value of the textbox4 is: ".$textbox_val4);

    $logfile->logfile_writeline("the selected value of the dropdown1 is: ".$dd1_type);
    $logfile->logfile_writeline("the selected value of the dropdown2 is: ".$dd2_type);
    $logfile->logfile_writeline("the selected value of the dropdown3 is: ".$dd3_type);
    $logfile->logfile_writeline("the selected value of the dropdown4 is: ".$dd4_type);

    $count = 0;

    if($dd1_type == "alphabetical order" || $dd1_type == "numeric order"){
        $count++; 
    }
    if($dd2_type == "alphabetical order" || $dd2_type == "numeric order"){
        $count++; 
    }
    if($dd3_type == "alphabetical order" || $dd3_type == "numeric order"){
        $count++; 
    }
    if($dd4_type == "alphabetical order" || $dd4_type == "numeric order"){
        $count++; 
    }


    $logfile->logfile_writeline("the count is:: ".$count);

    $raw_json["ret_message"] ="";
    if($count == 0){
        $logfile->logfile_writeline("All the dropdowns cannot be fixed");
        $raw_json["ret_code"] = 1;
        $raw_json["ret_message"] = "All the dropdowns cannot be fixed";
    }else if($count > 1){
        $logfile->logfile_writeline("More than 1 field cannot be variable");
        $raw_json["ret_code"] = 2;
        $raw_json["ret_message"] = "More than 1 field cannot be variable";
    }

    $dd1_validate_result = validate_name_format($logfile, $dd1_type, $textbox_val1);
    $raw_json["ret_code1"] = $dd1_validate_result["ret_code"] ;
    $raw_json["ret_message1"] = $dd1_validate_result["ret_message"] ;

    $dd2_validate_result = validate_name_format($logfile, $dd2_type, $textbox_val2);
    $raw_json["ret_code2"] = $dd2_validate_result["ret_code"] ;
    $raw_json["ret_message2"] = $dd2_validate_result["ret_message"] ;

    $dd3_validate_result = validate_name_format($logfile, $dd3_type, $textbox_val3);
    $raw_json["ret_code3"] = $dd3_validate_result["ret_code"] ;
    $raw_json["ret_message3"] = $dd3_validate_result["ret_message"] ;

    $dd4_validate_result = validate_name_format($logfile, $dd4_type, $textbox_val4);
    $raw_json["ret_code4"] = $dd4_validate_result["ret_code"] ;
    $raw_json["ret_message4"] = $dd4_validate_result["ret_message"] ;
    $logfile->logfile_writeline("raw_json[ret_code]:: ".$raw_json["ret_code"]);
    $logfile->logfile_writeline("raw_json[ret_code1]:: ".$raw_json["ret_code1"]);
    $logfile->logfile_writeline("raw_json[ret_code2]:: ".$raw_json["ret_code2"]);
    $logfile->logfile_writeline("raw_json[ret_code3]:: ".$raw_json["ret_code3"]);
    $logfile->logfile_writeline("raw_json[ret_code4]:: ".$raw_json["ret_code4"]);


    if(($raw_json["ret_code"] == 0) && ($raw_json["ret_code1"] == 0) && ($raw_json["ret_code2"] == 0) && ($raw_json["ret_code3"] == 0) && ($raw_json["ret_code4"] == 0)){
        if($dd1_type == "alphabetical order" || $dd1_type == "numeric order" || $dd1_type == NULL){
            $str1 = "";
            $str2 = $textbox_val2.$textbox_val3.$textbox_val4;
            $start = $textbox_val1;
        }else if($dd2_type == "alphabetical order" || $dd2_type == "numeric order" || $dd2_type == NULL){
            $str1 = $textbox_val1;
            $str2 = $textbox_val3.$textbox_val4;
            $start = $textbox_val2;
        }else if($dd3_type == "alphabetical order" || $dd3_type == "numeric order" || $dd3_type == NULL){
            $str1 = $textbox_val1.$textbox_val2;
            $str2 = $textbox_val4;
            $start = $textbox_val3;
        }else if($dd4_type == "alphabetical order" || $dd4_type == "numeric order" || $dd4_type == NULL){
            $str1 = $textbox_val1.$textbox_val2.$textbox_val3;
            $str2 = "";
            $start = $textbox_val4;
        }
        $logfile->logfile_writeline("the value of str1 is:: ".$str1);
        $logfile->logfile_writeline("the value of str2 is:: ".$str2);

        $parent_id=$node_id;
        $parent_id_mapped = $node_id_mapped;
        for($i = 0; $i<$prop_num ; $i++){
            
            $logfile->logfile_writeline("the value of start is:: ".$start);
            $node_name = $str1.$start.$str2;
            
            $logfile->logfile_writeline("the node name is:: ".$node_name);
            $start++;

            if ($parent_id != "#") {
                $sql_prop_topo = "INSERT INTO prop_topo(prop_id, node_name, parent_id, unit) VALUES (?, ?, ?, ?)";
                $logfile->logfile_writeline(__FILE__."SQL::".$sql_prop_topo);
                $sql_prop_topo_temp = $conn->prepare($sql_prop_topo);
                $logfile->logfile_writeline(__FILE__."SQL::prop_id=".$prop_id);
                $logfile->logfile_writeline(__FILE__."SQL::node_name=".$node_name);
                $logfile->logfile_writeline(__FILE__."SQL::parent_id_mapped=".$parent_id_mapped);
                $logfile->logfile_writeline(__FILE__."SQL::unit=".$rad_sel);

                $sql_prop_topo_temp->bind_param("isii", $prop_id, $node_name, $parent_id_mapped, $rad_sel);
                $logfile->logfile_writeline(__FILE__."SQL::Here1");
                $sql_prop_topo_temp->execute();
                $logfile->logfile_writeline(__FILE__."SQL::Here2");
                $last_id_prop_topo = $conn->insert_id;
                $logfile->logfile_writeline(__FILE__."SQL::Here3::Last ID=".$last_id_prop_topo);
                //Sec map this node ID
                
                $prop_topo_map = sec_get_map("prop_topo_map"); //get the topo sec_map table from session
                sec_clear_map("prop_topo_map"); //clear the sec_map table from session
                $rand_id = gen_unique_sec_id($prop_topo_map); //generate an unique random ID w.r.t prop sec_map table
                $prop_topo_map[$rand_id] = $last_id_prop_topo; //enter the new node's sec_map into the table
                sec_push_map("prop_topo_map", $prop_topo_map); //push the modified sec_map table into session
    
                $logfile->logfile_writeline(__FILE__."---Dumping topo_list sec_map: Begin");
                foreach ($prop_topo_map as $key => $val) {
                    $logfile->logfile_writeline($key." : ".$val);
                }
                $logfile->logfile_writeline(__FILE__."---Dumping topo_list sec_map: End");
            }


        }
    }

    $raw_json_encode=json_encode($raw_json);
    $logfile->logfile_writeline("output to ajax:: ".$raw_json_encode);
    echo $raw_json_encode;

    $session_val= is_session_valid();

    if($session_val==0){
        $prop_id = $SENSESSION->get_val("prop_id");
    }


    else{      
    }
    $conn->close();
}
$logfile->logfile_close();
?>