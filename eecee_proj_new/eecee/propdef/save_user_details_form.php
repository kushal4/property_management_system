<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$sess_user_id = $SENSESSION->get_val("user_id");

function check_alpha($input_val, $pri_sig, $logfile){
    $ret_obj=[];
    $retcode = "";
    if($input_val == "undefined"){
        $ret_obj["ret_code"] = 0;
        $ret_obj["ret_input_id"] = $pri_sig;
        $ret_obj["ret_input_val"] = $input_val;
    }else{
        if (!preg_match('/[^A-Za-z]+/', $input_val))
        {
            $logfile->logfile_writeline("it is <b>ONLY ALPHA </b>".$input_val);
            $ret_obj["ret_code"] = 0;
            $ret_obj["ret_input_id"] = $pri_sig;
            $ret_obj["ret_input_val"] = $input_val;
            //return $ret_obj;
        }else{
            $logfile->logfile_writeline("it is <b>NOT ONLY ALPHA </b>".$input_val);
            $ret_obj["ret_code"] = 1; //ERROR:: It is supposed to be only alphabets, but it's NOT
            $ret_obj["ret_input_id"] = $pri_sig;
            //return $ret_obj;
        }
    }
    return $ret_obj;
}


function check_number($input_val, $pri_sig, $special_char, $logfile){
    $logfile->logfile_writeline("getting inside check num::");
    $ret_obj=[];
    $retcode = "";
    echo "the input value is:::::::::::::::::".$input_val."\n";
    if($input_val == "undefined"){
        $ret_obj["ret_code"] = 0;
        $ret_obj["ret_input_id"] = $pri_sig;
        $ret_obj["ret_input_val"] = $input_val;
    }else{
        if (preg_match('/^[0-9]+$/', $input_val)) {

            $logfile->logfile_writeline("it is <b>ONLY NUMBERS </b>");
            $ret_obj["ret_code"] = 0;
            $ret_obj["ret_input_id"] = $pri_sig;
            $ret_obj["ret_input_val"] = $input_val;
            //return $ret_obj;
        }else {
            
            $logfile->logfile_writeline("the Input Value is:: ".$input_val);
            $typeOfInputVal = gettype($input_val);
            $logfile->logfile_writeline("the datatype of the Input Value is:: ".$typeOfInputVal);

            //$logfile->logfile_writeline("--------------------------------------------------- not number----------------------------");
            
            if (preg_match("/[+]/", $input_val)) {
            //if (preg_match('/[$special_char]/', $input_val)) {
            //if(preg_match('/['.$special_char.']i/', $input_val)){
            //if(preg_match("/$special_char/", $input_val)){
                $logfile->logfile_writeline("NUMBER WITH SPECIAL CHARS:: NO ERROR :) ");
                $ret_obj["ret_code"] = 0;
                $ret_obj["ret_input_id"] = $pri_sig;
                $ret_obj["ret_input_val"] = $input_val;
                //return $ret_obj;
            }else {
                $logfile->logfile_writeline("NUMBER WITH SPECIAL CHARS:: ERROR :( ");
                $ret_obj["ret_code"] = 2;//ERROR:: It is supposed to be only number, but it's NOT
                $ret_obj["ret_input_id"] = $pri_sig;
            }
             
            $logfile->logfile_writeline("--------------------------------------------------- the ret code is----------------------------".$ret_obj["ret_code"]);
            //return $ret_obj;
            
            
        }
    }
    return $ret_obj;    
}

function check_max($input_val, $max_length, $pri_sig, $data_type, $logfile){
    $ret_obj=[];
    $retcode = "";
    //$logfile->logfile_writeline("=====================================================");
    if($input_val == "undefined"){
        $ret_obj["ret_code"] = 0;
        $ret_obj["ret_input_id"] = $pri_sig;
        $ret_obj["ret_input_val"] = $input_val;
    }else{
        if($data_type == "num"){
            $numlength = strlen((string)$input_val);
            if($numlength > $max_length){
                $logfile->logfile_writeline("exceeded max length:: ");
                $ret_obj["ret_code"] = 4;// exceeds max length
                $ret_obj["ret_input_id"] = $pri_sig;
                $ret_obj["ret_input_val"] = $input_val;
            }else{
                $ret_obj["ret_code"] = 0;
                $ret_obj["ret_input_id"] = $pri_sig;
                $ret_obj["ret_input_val"] = $input_val;
            }
        }else if($data_type == "alpha" || $data_type == "alphanum"){
            $alphalength= strlen($input_val);
            if($alphalength > $max_length){
                $logfile->logfile_writeline("exceeded max length:: ");
                $ret_obj["ret_code"] = 4;// exceeds max length
                $ret_obj["ret_input_id"] = $pri_sig;
                $ret_obj["ret_input_val"] = $input_val;
            }else{
                $ret_obj["ret_code"] = 0;
                $ret_obj["ret_input_id"] = $pri_sig;
                $ret_obj["ret_input_val"] = $input_val;
            }
        }
    }
    return $ret_obj;
}

function check_min($input_val, $min_length, $pri_sig, $data_type, $logfile){
    echo "the min length is:".$min_length."\n";
    $ret_obj=[];
    $retcode = "";
    if($input_val == "undefined"){
        $ret_obj["ret_code"] = 0;
        $ret_obj["ret_input_id"] = $pri_sig;
        $ret_obj["ret_input_val"] = $input_val;
    }else{
        if($data_type == "num"){
            $numlength = strlen((string)$input_val);
            echo "the min length of the number is::".$numlength."\n";
            if($numlength < $min_length){
                $logfile->logfile_writeline("exceeded max length:: ");
                $ret_obj["ret_code"] = 5;// less than minimum
                $ret_obj["ret_input_id"] = $pri_sig;
                $ret_obj["ret_input_val"] = $input_val;
            }else{
                $ret_obj["ret_code"] = 0;
                $ret_obj["ret_input_id"] = $pri_sig;
                $ret_obj["ret_input_val"] = $input_val;
            }
        }else if($data_type == "alpha" || $data_type == "alphanum"){
            $alphalength = strlen($input_val);
            echo "the min length of the aphabet is::".$alphalength."\n";
            if($alphalength < $min_length){
                $logfile->logfile_writeline("exceeded max length:: ");
                $ret_obj["ret_code"] = 5;// less than minimum
                $ret_obj["ret_input_id"] = $pri_sig;
                $ret_obj["ret_input_val"] = $input_val;
            }else{
                $ret_obj["ret_code"] = 0;
                $ret_obj["ret_input_id"] = $pri_sig;
                $ret_obj["ret_input_val"] = $input_val;
            }
        }
    }
    return $ret_obj;
}

function check_mandatory($input_val, $pri_sig, $logfile){
    
}

function check_alphaNum($input_val, $pri_sig, $special_char, $logfile){
    $ret_obj=[];
    $retcode = "";
    if($input_val == "undefined"){
        $ret_obj["ret_code"] = 0;
        $ret_obj["ret_input_id"] = $pri_sig;
        $ret_obj["ret_input_val"] = $input_val;
        //return $ret_obj;
    }else{
        if (!preg_match('/[^A-Za-z0-9]/', $input_val))
        {
            $logfile->logfile_writeline("it is <b>ONLY ALPHA </b>".$input_val);
            $ret_obj["ret_code"] = 0;
            $ret_obj["ret_input_id"] = $pri_sig;
            $ret_obj["ret_input_val"] = $input_val;
            //return $ret_obj;
        }else{
            if($pri_sig == "USRPROF_ADD_STR1" || $pri_sig == "USRPROF_ADD_STR2" ){
                if (preg_match('/\s/', $input_val)){
                    $ret_obj["ret_code"] = 0;
                    $ret_obj["ret_input_id"] = $pri_sig;
                    $ret_obj["ret_input_val"] = $input_val;
                }
            }else{
                $logfile->logfile_writeline("it is <b>NOT ONLY ALPHA </b>".$input_val);
                //if ( preg_match('/\s/',$username) )
                $ret_obj["ret_code"] = 3; //ERROR:: It is supposed to be only alpha-numeric, but it's NOT
                $ret_obj["ret_input_id"] = $pri_sig;
                //return $ret_obj;
            }
            
        }
    }
    return $ret_obj;
}

function checkValidationOfValues($pri_sig, $conn, $input_val, $logfile){
    $retObj=[];
    $retCode = "";
    $logfile->logfile_writeline("the input value is:: ".$input_val);
    $logfile->logfile_writeline("getting inside checkValidationOfValues");
    $field_map_sql = "SELECT * FROM field_map WHERE pri_sig = ?";
    $field_map_stmt = $conn->prepare($field_map_sql);
    $field_map_stmt->bind_param("s",$pri_sig);
    $field_map_stmt->execute();
    $field_map_result = $field_map_stmt->get_result();
    //$logfile->logfile_writeline("======the number of rows are======:: ".mysqli_num_rows($field_map_result));
    $field_map_res_row = $field_map_result->fetch_assoc();
    $data_type = $field_map_res_row['data_type'];
    $special_char = $field_map_res_row['sp_char'];
    $min_length = $field_map_res_row['min'];
    $max_length = $field_map_res_row['max'];
    $logfile->logfile_writeline("the datatype of the perm_sig is:: ".$data_type);
    $logfile->logfile_writeline("the special character of the perm_sig is:: ".$special_char);

    $checkMinRetObj = check_min($input_val, $min_length, $pri_sig, $data_type, $logfile);
    $checkMinRetObjRetCode = $checkMinRetObj["ret_code"];
    $checkMinRetObjRetInputID = $checkMinRetObj["ret_input_id"];
    
    if($checkMinRetObjRetCode == 0){
        $checkMaxRetObj = check_max($input_val, $max_length, $pri_sig, $data_type, $logfile);
        $checkMaxRetObjRetCode = $checkMaxRetObj["ret_code"];
        $checkMaxRetObjRetInputID = $checkMaxRetObj["ret_input_id"];
        if($checkMaxRetObjRetCode == 0){
            if ($data_type == "alpha"){
                $checkAlphaReturnObj = check_alpha($input_val, $pri_sig, $logfile);
                $checkAlphaReturnObjRetCode = $checkAlphaReturnObj["ret_code"];
                $checkAlphaReturnObjRetInputID = $checkAlphaReturnObj["ret_input_id"];
                if($checkAlphaReturnObjRetCode == 0){
                    $retObj["ret_code"] = 0;
                    $retObj["ret_input_id"] = $checkAlphaReturnObjRetInputID;
                    $ret_obj["ret_input_val"] = $input_val;
                    //return $retObj;
                }else{
                    $retObj["ret_code"] = $checkAlphaReturnObjRetCode;
                    $retObj["ret_input_id"] = $checkAlphaReturnObjRetInputID;
                    //return $retObj;
                }
            }else if($data_type == "num"){
                $checkNumReturnObj = check_number($input_val, $pri_sig, $special_char, $logfile);
                $checkNumReturnObjRetCode = $checkNumReturnObj["ret_code"];
                $checkNumReturnObjRetInputID = $checkNumReturnObj["ret_input_id"];
                if($checkNumReturnObjRetCode == 0){
                    $retObj["ret_code"] = 0;
                    $retObj["ret_input_id"] = $checkNumReturnObjRetInputID;
                    $ret_obj["ret_input_val"] = $input_val;
                    //return $retObj;
                }else{
                    $retObj["ret_code"] = $checkNumReturnObjRetCode;
                    $retObj["ret_input_id"] = $checkNumReturnObjRetInputID;
                    //return $retObj;
                }
            }else if($data_type == "alphanum"){
                $checkAlphaNumReturnObj = check_alphaNum($input_val, $pri_sig, $special_char, $logfile);
        
                $checkAlphaNumReturnObjRetCode = $checkAlphaNumReturnObj["ret_code"];
                $checkAlphaNumReturnObjRetInputID = $checkAlphaNumReturnObj["ret_input_id"];
                if($checkAlphaNumReturnObjRetCode == 0){
                    $retObj["ret_code"] = 0;
                    $retObj["ret_input_id"] = $checkAlphaNumReturnObjRetInputID;
                    $ret_obj["ret_input_val"] = $input_val;
                    //return $retObj;
                }else{
                    $retObj["ret_code"] = $checkAlphaNumReturnObjRetCode;
                    $retObj["ret_input_id"] = $checkAlphaNumReturnObjRetInputID;
                    //return $retObj;
                }
            }
        }else{
            $retObj["ret_code"] = $checkMaxRetObjRetCode;
            $retObj["ret_input_id"] = $checkMaxRetObjRetInputID;
        }

    }else{
        $retObj["ret_code"] = $checkMinRetObjRetCode;
        $retObj["ret_input_id"] = $checkMinRetObjRetInputID;
    }

    
    $logfile->logfile_writeline("=====================================================");
    return $retObj;
}


if (is_ajax()) {
    
    $myArray = [];
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $edit_elem_arr="";
    $controler_id="";

    foreach ($json_decoded as $key => $value) {
        if ($key=="edit_elem_arr"){
            $edit_elem_arr = $value;
        }
        if ($key=="controler_id"){
            $controler_id = $value;
        }
    }

    $retOBjectArray = array();
    //$edit_elem_arr_str = var_export($edit_elem_arr, true);
   // $logfile->logfile_writeline("the element array is".$edit_elem_arr_str);
    //$logfile->logfile_writeline("the encoded controler ID is".$controler_id);
    //$decoded_controler_id = sec_get_map_val ("perm_sig_map", $controler_id);
   // $logfile->logfile_writeline("the decoded controler ID is".$decoded_controler_id);

    foreach ($edit_elem_arr as $key => $value) {
        //$val_str = var_export($value, true);
        $inputValue = "";
        $input_id = $value["input_id"];
        $input_val = $value["input_val"];
        $logfile->logfile_writeline("the encoded input ID is:: ".$input_id);
        $decoded_input_id = sec_get_map_val ("fld_sig_map", $input_id);
        $logfile->logfile_writeline("the decoded input ID is:: ".$decoded_input_id);
        //$logfile->logfile_writeline("the input value is:: ".$input_val);
        //$logfile->logfile_writeline("=====================================================");
        if($decoded_input_id == "USRPROF_PH2_CCODE" || $decoded_input_id == "USRPROF_PH3_CCODE" || $decoded_input_id == "USRPROF_PH4_CCODE" ||  $decoded_input_id == "USRPROF_EMG_PH1_CCODE" || $decoded_input_id == "USRPROF_EMG_PH2_CCODE"){
                if($input_val === "undefined"){
                    $inputValue = "undefined";
                }else{
                    //echo "the "
                    $decoded_phn_code = sec_get_map_val ("phone_code_sig_map", $input_val);
                    $logfile->logfile_writeline("*************The decoded phone code is************:: ".$decoded_phn_code);
                    //checkValidationOfValues($decoded_input_id, $conn, $inputValue, $logfile);
                    $phCode_sql = "SELECT * FROM master_phone_code where id = ?";
                    $phCode_temp = $conn->prepare($phCode_sql);
                    $phCode_temp->bind_param("i",$decoded_phn_code);
                    $phCode_temp->execute();
                    $phCode_temp_result = $phCode_temp->get_result();
                    $phCode_row = $phCode_temp_result->fetch_assoc();
                    $phoneCode = $phCode_row['phone_code'];
                    $logfile->logfile_writeline("The phone code is:: ".$phoneCode);
        
                    $inputValue = $phoneCode;
                }
                    
            //$typeOfInputVal = gettype($input_val);
            //$logfile->logfile_writeline("----------------------------------------the data type is ".$input_val);
        }
        /*
        else{
            checkValidationOfValues($decoded_input_id, $conn, $input_val, $logfile);
        }*/
        else if($decoded_input_id == "USRPROF_ADD_COUNTRY"){
            if($input_val === "undefined"){
            }else{
                $decoded_country_id = sec_get_map_val ("country_sig_map", $input_val);
                $country_sql = "SELECT * FROM master_country where id = ?";
                $country_temp = $conn->prepare($country_sql);
                $country_temp->bind_param("i",$decoded_country_id);
                $country_temp->execute();
                $country_result = $country_temp->get_result();
                $country_result_row = $country_result->fetch_assoc();
                $country_name = $country_result_row['name'];
                $logfile->logfile_writeline("The country name is:: ".$country_name);
                $inputValue = $country_name;
            }
            
        }else if($decoded_input_id == "USRPROF_ADD_STATE"){
            if($input_val === "undefined"){
            }else{
                $decoded_state_id = sec_get_map_val ("state_sig_map", $input_val);
                $state_sql = "SELECT * FROM master_state where id = ?";
                $state_temp = $conn->prepare($state_sql);
                $state_temp->bind_param("i",$decoded_state_id);
                $state_temp->execute();
                $state_result = $state_temp->get_result();
                $state_result_row = $state_result->fetch_assoc();
                $state_name = $state_result_row['name'];
                $logfile->logfile_writeline("The state name is:: ".$state_name);
                $inputValue = $state_name;
            }
        }else if($decoded_input_id == "USRPROF_ADD_CITY"){
            if($input_val === "undefined"){
            }else{
                $decoded_city_id = sec_get_map_val ("city_sig_map", $input_val);
                $city_sql = "SELECT * FROM master_city where id = ?";
                $city_temp = $conn->prepare($city_sql);
                $city_temp->bind_param("i",$decoded_city_id);
                $city_temp->execute();
                $city_result = $city_temp->get_result();
                $city_result_row = $city_result->fetch_assoc();
                $city_name = $city_result_row['name'];
                $logfile->logfile_writeline("The city name is:: ".$city_name);
                $inputValue = $city_name;
            }
        }else if($decoded_input_id == "USRPROF_GENDER"){
            if($input_val === "undefined"){
            }else{
                $decoded_gender_id = sec_get_map_val ("gender_sig_map", $input_val);
                $gender_sql = "SELECT * FROM master_gender where id = ?";
                $gender_temp = $conn->prepare($gender_sql);
                $gender_temp->bind_param("i",$decoded_city_id);
                $gender_temp->execute();
                $gender_result = $gender_temp->get_result();
                $gender_result_row = $gender_result->fetch_assoc();
                $gender_name = $gender_result_row['gender'];
                $logfile->logfile_writeline("The gender name is:: ".$gender_name);
                $inputValue = $gender_name;
            }
        }else if($decoded_input_id == "USRPROF_BLD_GRP"){
            if($input_val === "undefined"){
            }else{
                $decoded_bloodGrp_id = sec_get_map_val ("gender_sig_map", $input_val);
                //$logfile->logfile_writeline("$$$$$$$$    GENDER    $$$$$$$$");
                //$logfile->logfile_writeline("The blood group is:: ".$decoded_bloodGrp_id);
                $bldGrp_sql = "SELECT * FROM master_blood_group where id = ?";
                $bldGrp_temp = $conn->prepare($bldGrp_sql);
                $bldGrp_temp->bind_param("i",$decoded_bloodGrp_id);
                $bldGrp_temp->execute();
                $bldGrp_result = $bldGrp_temp->get_result();
                $bldGrp_result_row = $bldGrp_result->fetch_assoc();
                $bloodGrp = $bldGrp_result_row['blood_grp'];
                $logfile->logfile_writeline("The blood group name is:: ".$bloodGrp);
                $inputValue = $bloodGrp;
            }
        }else{
            $inputValue = $input_val;
            
            if($input_val === ""){
            $inputValue = "undefined";
            
            }else{
                $inputValue = $input_val;
            }

        }
        $return_object = checkValidationOfValues($decoded_input_id, $conn, $inputValue, $logfile);
        $return_code = $return_object["ret_code"];
        $return_inputID = $return_object["ret_input_id"];
        $return_inputVal = $inputValue;
        echo "the input ID is::".$return_inputID."\n";
        echo "the input value is::".$return_inputVal."\n";

        $retOBjectArray_sub_array["retcode"] = $return_code; 
        $retOBjectArray_sub_array["ret_input_id"] = $return_inputID; 
        $retOBjectArray_sub_array["ret_input_val"] = $return_inputVal; 
        $pushed_array = array_push($retOBjectArray,$retOBjectArray_sub_array);
    }

    //print_r($retOBjectArray);
    $count = 0;
    $ret_code_array_json = array();
    foreach ($retOBjectArray as $key => $value) {
        $ret_code_subarray_json["retcode"] = $value["retcode"];
        $ret_code_subarray_json["ret_input_id"] = $value["ret_input_id"];
        $ret_code_subarray_json["ret_input_val"] = $value["ret_input_val"];
        //echo "the retcode is:".$the_ret_code."\n";
        if($ret_code_subarray_json["retcode"] != 0){
            $count++;
            $pushed_array = array_push($ret_code_array_json, $ret_code_subarray_json);
        }else{
            update_data_tbl_fld_value_in_db_by_fld_sig ($conn, $value["ret_input_id"], $value["ret_input_val"], $sess_user_id, $sess_user_id, "user_id=".$sess_user_id);
        }
    }

    print_r($ret_code_array_json);

    if($count == 0){ //everything is smooth can save to database
        $raw_json["ret_code"] = 0;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }else{
        $raw_json_encode=json_encode($ret_code_array_json);
        echo $raw_json_encode;
    }

    $session_val= is_session_valid();

    if($session_val==0){
        
    }


    else{      
    }
    $conn->close();
}
$logfile->logfile_close();
?>