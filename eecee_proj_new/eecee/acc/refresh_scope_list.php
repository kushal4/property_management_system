<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';
include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'sec.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function get_role_by_sig_local($role_sig, $role_arr, $logfile){
    foreach ($role_arr as $value){  
        if($value["sig"] == $role_sig){
            return $value;
        }
    }
}

$logfile->logfile_writeline("In Refresh_scope_list.php");

if (is_ajax()) {

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $logfile->logfile_writeline("AJAX Parameter:BEGIN"); 
    $logfile->logfile_writeline($raw_json_str); 
    $logfile->logfile_writeline("AJAX Parameter:END"); 

    $json_decoded = json_decode($raw_json_str, true);
    $role_sig=""; 
    foreach ($json_decoded as $key => $value) {
        if ($key=="role_sig"){
            $role_sig = $value;
        }  
    }
    $logfile->logfile_writeline("the target role sig is:: ".$role_sig);

    $roles = array();
    $dbroles = array();
    if($role_sig == NULL || $role_sig == ""){
        $raw_json["ret_code"] = 0;
        $raw_json["roles_array"] = $roles;

        $logfile->logfile_writeline("enterred if part");

    }else{
            $logfile->logfile_writeline("enterred else part");
            $logfile->logfile_writeline("refresh_scope_list -------- here step 01");
            $prop_id = $SENSESSION->get_val("prop_id");
            $target_role_sig_decoded = sec_get_map_val ("role_sig_by_perm_map", $role_sig);
            $logfile->logfile_writeline("the encoded targeted role sig is-----------------------------------------------".$role_sig);
            $logfile->logfile_writeline("the decoded targeted role sig is-----------------------------------------------".$target_role_sig_decoded);
            $logfile->logfile_writeline("the prop_id is :: ".$prop_id);
            
            $check_role_scp = "SELECT scope_role_sig FROM acc_man_role_scope WHERE prop_id = ? AND target_role_sig = ?";
            //$logfile->logfile_writeline("the select statement:: ".$check_role_scp);
            $role_scp_result_temp = $conn->prepare($check_role_scp);
            if($role_scp_result_temp){
                $logfile->logfile_writeline("prepare is successful");
                $bind = $role_scp_result_temp->bind_param("is",$prop_id, $target_role_sig_decoded);
                if($bind){
                    $logfile->logfile_writeline("bind is successful");
                    $execute = $role_scp_result_temp->execute();
                    if($execute){
                        $logfile->logfile_writeline("execute is successful");
                        $check_role_scp_result = $role_scp_result_temp->get_result();
                        $result_all = $check_role_scp_result->fetch_all();
                        $num_rows = mysqli_num_rows($check_role_scp_result);
                        $logfile->logfile_writeline("refresh_scope_list -------- here step 02");
                        $logfile->logfile_writeline("the number of row is".$num_rows);
                    }else{
                        $logfile->logfile_writeline("execute is failure");
                    }
                    
                }else{
                    $logfile->logfile_writeline("bind is failure");
                }
                
            }else{
                $logfile->logfile_writeline("prepare is failure");
            }
            $logfile->logfile_writeline("NumRows=".$num_rows);
            if($num_rows == 0){
                $roles = array();
                $raw_json["ret_code"] = 0;
                $raw_json["roles_array"] = $roles;

                $raw_json_encode=json_encode($raw_json);
                $logfile->logfile_writeline("AJAX return :: ".$raw_json_encode);
                echo $raw_json_encode;
                
            }else{
                $logfile->logfile_writeline("refresh_scope_list -------- here step 03");
                foreach ($result_all as $value) {
                    $role_scope_db_sig = $value[0];
                    
                    $logfile->logfile_writeline("role_scope_db_sig is :::::::::: here step 04. role_scope_db_sig=".$role_scope_db_sig);
                    array_push($roles,$role_scope_db_sig);

                    

                } 
                $actlGetRoleDetsCurlURL = $actl_urls->actlGetRoleDetsCurlURL;

                $projectKey = $projectID;
                $hierarchy_true = "true";
                $hierarchy_false = "false";
    
                $data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id, "sig_arr"=>$roles, "hierarchy"=>$hierarchy_false));
                $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
                $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL
                $decodedJson = json_decode($actlRetObj);
                $jsonDataSTR = $decodedJson->d;
                $data_json_decode = json_decode($jsonDataSTR);
                $perm_array = $data_json_decode->p;
                $api_roles = $perm_array->roles; 

                
                
                $ret_roles = array();
                foreach ($api_roles as $value){  
                    
                    $ret_role = new stdClass();
                    $api_role_sig = $value->sig;
                    $api_role_name = $value->name;
                    $api_role_type = $value->type;
                    $logfile->logfile_writeline("the node sig is:: ".$api_role_sig);
                    $logfile->logfile_writeline("the node name is:: ".$api_role_name);
                    $logfile->logfile_writeline("the node type is:: ".$api_role_type);
                    $secedFeatCatSig = sec_push_val_single_entry ("api_role_sig", $api_role_sig);
                    $ret_role->sig = $secedFeatCatSig; //sec-mapped node_id
                    $ret_role->text = $value->name;
                    array_push($ret_roles,$ret_role);
                    
                    if($api_role_type == "role"){
                        $ret_role->sig = $secedFeatCatSig; //sec-mapped node_id
                        $ret_role->text = $value->name;
                        array_push($dbroles,$ret_role);
                    }
                }
                $ret_roles_str = var_export($ret_roles, true);
                $logfile->logfile_writeline("here step 5 ::  ".$ret_roles_str);

                $dbroles_str = var_export($dbroles, true);
                $logfile->logfile_writeline("here step 5.5 ::  ".$dbroles_str);

                

                $raw_json["ret_code"] = 0;
                $raw_json["roles_array"] = $dbroles;

                $raw_json_encode=json_encode($raw_json);
                $logfile->logfile_writeline("AJAX return :: ".$raw_json_encode);
                echo $raw_json_encode;
                
            }
            
    }
}

?>