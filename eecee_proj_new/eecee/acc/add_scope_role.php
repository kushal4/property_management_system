<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function get_role_by_sig_local($role_sig, $role_arr, $logfile){
    foreach ($role_arr as $value){  
        if($value->sig == $role_sig){
            return $value;
        }
    }
}

if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $scope_role_sig=""; 
    $target_role_sig=""; 
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="scope_role_sig"){
            $scope_role_sig = $value;
        }

        if ($key=="target_role_sig"){
            $target_role_sig = $value;
        }
        
    }
   
    $scope_role_sig_decoded = sec_get_map_val ("role_sig_role_scope_map", $scope_role_sig);
    $logfile->logfile_writeline("the encoded scope role sig is-----------------------------------------------".$scope_role_sig);
    $logfile->logfile_writeline("the decoded scope role sig is-----------------------------------------------".$scope_role_sig_decoded); 

    $target_role_sig_decoded = sec_get_map_val ("role_sig_by_perm_map", $target_role_sig);
    $logfile->logfile_writeline("the encoded targeted role sig is-----------------------------------------------".$target_role_sig);
    $logfile->logfile_writeline("the decoded targeted role sig is-----------------------------------------------".$target_role_sig_decoded); 

    $curr_map = sec_get_map("role_sig_map");
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map 01  MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map 01 MAP: End");

    $curr_map02 = sec_get_map("role_sig_role_scope_map");
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map 02 MAP: Begin");
            foreach($curr_map02 as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map 02 MAP: End");

    $curr_map03 = sec_get_map("role_sig_by_perm_map");
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map 03 MAP: Begin");
            foreach($curr_map03 as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map 03 MAP: End");

    $curr_map04 = sec_get_map("api_role_sig");
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map 04 MAP: Begin");
            foreach($curr_map03 as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map 04 MAP: End");

    $role_sig = $SENSESSION->get_val("role_sig");
    $prop_id = $SENSESSION->get_val("prop_id");
    $user_id = $SENSESSION->get_val("user_id");

    $logfile->logfile_writeline("the propperty ID is---------".$prop_id);
    $logfile->logfile_writeline("the loggen in role sig is---------".$role_sig);
    $logfile->logfile_writeline("the logged in user ID is---------".$user_id);


    $check_role_scp = "SELECT scope_role_sig FROM acc_man_role_scope WHERE prop_id = ? AND target_role_sig = ? AND scope_role_sig = ?";
    $role_scp_result_temp = $conn->prepare($check_role_scp);
    $role_scp_result_temp->bind_param("iss",$prop_id, $target_role_sig_decoded, $scope_role_sig_decoded);
    $role_scp_result_temp->execute();
    $check_role_scp_result = $role_scp_result_temp->get_result();
    $result_all = $check_role_scp_result->fetch_all();
    $num_rows = mysqli_num_rows($check_role_scp_result);
    $logfile->logfile_writeline("**getting inside this** 1");
    $logfile->logfile_writeline("**the number of rows**".$num_rows);
    if($num_rows == 0){
        $logfile->logfile_writeline("**getting inside this 2**");
        $role_scp = "INSERT INTO acc_man_role_scope(prop_id, target_role_sig, scope_role_sig, mod_by_role, mod_by_user) VALUES (?, ?, ?, ?, ?)";
        $role_scp_temp = $conn->prepare($role_scp);
        $role_scp_temp->bind_param("isssi",$prop_id, $target_role_sig_decoded, $scope_role_sig_decoded, $role_sig, $user_id);
        $role_scp_temp->execute();
    }

    //$result_all_str = var_export($result_all, true);
    //$logfile->logfile_writeline("acc_man_role_scope :: database: before array push ".$result_all_str);

    $role_sig_arr = array();
    foreach ($result_all as $key => $value) {
        $role_sig = $value["0"];
        array_push($role_sig_arr,$role_sig);
    }   

    $actlGetRoleDetsCurlURL = $actl_urls->actlGetRoleDetsCurlURL;
    //$role_sig_arr_str = var_export($role_sig_arr, true);
    //$logfile->logfile_writeline("acc_man_role_scope :: database: after array push ".$role_sig_arr_str);

    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "sig_arr"=>$role_sig_arr));
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL

    $decodedJson = json_decode($actlRetObj);
    $jsonDataSTR = $decodedJson->d;
    $data_json_decode = json_decode($jsonDataSTR);

    $perm_array = $data_json_decode->p;
    $roles = $perm_array->roles; 

    //$roles_str = var_export($roles, true);
    //$logfile->logfile_writeline("role array from ACTL response: ".$roles_str);
    $scope_role_arr = array();

    foreach ($roles as $value){  
        $role_sig = $value->sig;
        $role_type = $value->type;
        
        if($role_type == "role"){
            $role = new stdClass();
            $role->sig= $value->sig;
            $role->name= $value->name;
            array_push($scope_role_arr, $role);
        }
        
    }

    $scope_role_arr_str = var_export($scope_role_arr, true);
    $logfile->logfile_writeline("scope role array :: ".$scope_role_arr_str);

    $raw_json["ret_code"] = 0;
    $raw_json["target_role_sig"] = $target_role_sig;
    $raw_json["scope_role_array"] = $scope_role_arr;
    $raw_json_encode=json_encode($raw_json);
    $logfile->logfile_writeline("add_scope_role :: AJAX return :: ".$raw_json_encode);
    echo $raw_json_encode;
}   
?>