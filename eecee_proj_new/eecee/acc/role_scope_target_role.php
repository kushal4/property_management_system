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

function get_role_tree($role_sig, $role_arr, &$sec_map_arr, $logfile){
    $rand_id = gen_unique_sec_id($sec_map_arr); 
    $logfile->logfile_writeline("111111111111111".$rand_id); 

    $sec_map_arr[$rand_id]=$role_sig;

    $role_tree_obj = array();
    $role_tree_obj["children"] = array();

    $curr_role = get_role_by_sig($role_sig, $role_arr, $logfile);
    $role_tree_obj["id"] = $rand_id; //sec-mapped node_id
    $role_tree_obj["text"] = $curr_role["name"];
    //$role_tree_obj["reserved"] = $curr_role["reserved"];
    
    if ($curr_role["type"]=="rolecat") {
        $role_tree_obj["type"] = "rolecat";
        if ($curr_role["reserved"]==1) {
            $role_tree_obj["type"] = "cat_res";
        }
        if ($curr_role["active"]==0) {
            $role_tree_obj["type"] = "cat_deact";
        }

    }
    if ($curr_role["type"]=="role") {
        $role_tree_obj["type"] = "role";
        if ($curr_role["reserved"]==1) {
            $role_tree_obj["type"] = "role_res";
        }
        if ($curr_role["decommissioned"]==1) {
            $role_tree_obj["type"] = "role_dcm";
        }
        /*
        if ($curr_role["active"]==0) {
            $role_tree_obj["type"] = "role_deact";
        }*/
        if ($curr_role["linked_sig"]!=NULL) {
            $role_tree_obj["type"] = "role_link";
        }

    }

    $children = get_role_child($role_sig, $role_arr, $logfile); //Find children

    foreach ($children as $child){  
        $child_sig = $child["sig"];
        $ret_child =  get_role_tree($child_sig, $role_arr, $sec_map_arr, $logfile);
        array_push($role_tree_obj["children"], $ret_child);
    }
   //echo __FUNCTION__."::role_tree_obj=".$role_tree_obj."<br><br>";
   //print_r($role_tree_obj);

   return $role_tree_obj;
   
}

if (is_ajax()) {
    
    $raw_json_str = $_POST["k"];
    $logfile->logfile_writeline("AJAX Parameter:BEGIN"); 
    $logfile->logfile_writeline($raw_json_str); 
    $logfile->logfile_writeline("AJAX Parameter:END"); 

    $json_decoded = json_decode($raw_json_str, true);
    $target_role_sig=""; 
    foreach ($json_decoded as $key => $value) {
        if ($key=="target_role_sig"){
            $target_role_sig = $value;
        }  
    }
    $logfile->logfile_writeline("the TARGET_ROLE_SIG is ::".$target_role_sig); 

    if($target_role_sig == "0"){ // step 3.1.1.

        $logfile->logfile_writeline("getting inside target_role_sig = 0"); 
        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT_02";
        $tree_root_obj["text"] = "Valid Scope Roles";
        $role_tree = array();
        $obj = array(
            'ret_code'=>0,
            'root'=>$tree_root_obj,
            'role'=>$role_tree 
        );
        $obj_json = json_encode($obj);
        $logfile->logfile_writeline("RoleTree:BEGIN"); 
        $logfile->logfile_writeline($obj_json); 
        $logfile->logfile_writeline("RoleTree:END"); 
        echo $obj_json;

    }else{ // step 3.1.2. starts here

        $logfile->logfile_writeline("getting inside target_role_sig != 0"); 
        $target_role_sig_decoded = sec_get_map_val ("role_sig_by_perm_map", $target_role_sig);
        $logfile->logfile_writeline("the encoded targeted role sig is-----------------------------------------------".$target_role_sig);
        $logfile->logfile_writeline("the decoded targeted role sig is-----------------------------------------------".$target_role_sig_decoded);

        $conn = new \mysqli($server_name, $user_name, $password, $dbname);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $prop_id = $SENSESSION->get_val("prop_id");
        $feature_sig = "ACC_MAN_USR";
        //$feature_sig_02 = "ACC_MAN_ROLE";
        $enable_false = "true";
        $hierarchy_true = "true";

        $actlGetRoleTreeCurlURL = $actl_urls->actlGetRoleTreeCurlURL;

        
        ////////////////////////////////////////  Role 02 Beginning /////////////////////////////////////
        $role_sig = $SENSESSION->get_val("role_sig");
        $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "role_sig"=>$role_sig));
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
        $actlRetObj = CurlSendPostJson($actlGetRoleTreeCurlURL, $data_str, $logfile); //what we get from the ACTL
        $decodedJson = json_decode($actlRetObj, true);
        $jsonDataSTR = $decodedJson['d']."\n";
        $data_json_decode = json_decode($jsonDataSTR, true);
        $perm_array = $data_json_decode["p"];
        $SENSESSION->token("perm_obj", $perm_array);
        $roles_02 = $perm_array["roles"];//=================== role 02
        $roles_02_str = var_export($roles_02, true);
        $logfile->logfile_writeline("role 2 ::  ".$roles_02_str);

        // step 3.1.2. ends here
        ////////////////////////////////////////  Role 02 Ends /////////////////////////////////////

        // step 3.1.2.1. starts here:: step 01
        $array01 = array();
        foreach ($roles_02 as $value){  
            
            
        }
        $step1_role_arr_str = var_export($array01, true);
        $logfile->logfile_writeline("step 1: array01 :: ".$step1_role_arr_str);
        $logfile->logfile_writeline("step 1 ends");
        // step 3.1.2.1. ends here:: step 01
        // step 3.1.2.2. starts here:: step 02
        foreach ($roles_02 as $value){  
            
            if (array_key_exists("editable",$value)){
                //$logfile->logfile_writeline("********************** ");
                //array_push($array01, $value);
                
                if($value["editable"] == true){
                    array_push($array01, $value);
                }
            }
        }
        $step2_role_arr_str = var_export($array01, true);
        $logfile->logfile_writeline("step 2: array01 :: ".$step2_role_arr_str);
        $logfile->logfile_writeline("step 2 ends");
        // step 3.1.2.2. ends here:: step 02
        

        // step 3.1.2.3. starts here:: step 03
        $array02 = array();

        $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "role_sig"=>$target_role_sig_decoded));
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
        $actlRetObj = CurlSendPostJson($actlGetRoleTreeCurlURL, $data_str, $logfile); //what we get from the ACTL
        $decodedJson = json_decode($actlRetObj, true);
        $jsonDataSTR = $decodedJson['d']."\n";
        $data_json_decode = json_decode($jsonDataSTR, true);
        $perm_array = $data_json_decode["p"];
        $SENSESSION->token("perm_obj", $perm_array);
        $roles_03 = $perm_array["roles"];//=================== role 02
        $roles_03_str = var_export($roles_03, true);
        $logfile->logfile_writeline("role 3 ::  ".$roles_03_str);

        foreach ($array01 as $value){  
            
            $sig = $value["sig"];
            $roles03_r = get_role_by_sig_local($sig, $roles_03, $logfile);
            $roles03_r_str = var_export($roles03_r, true);
            $logfile->logfile_writeline("######### ".$roles03_r_str);
            if($roles03_r["editable"] == false && $roles03_r["type"] == "role"){
                $logfile->logfile_writeline("editable false");
                $logfile->logfile_writeline("editable false".$roles03_r["name"]);
                //$logfile->logfile_writeline("false roles".$false_roles);
                array_push($array02, $roles03_r);
            }
        }
        $array02_str = var_export($array02, true);
        $logfile->logfile_writeline("step 3 : array02 ::  ".$array02_str);
        $logfile->logfile_writeline("step 3 ends");
        // step 3.1.2.3. ends here:: step 03

        // step 3.1.2.4. starts here:: step 04
        $array03 = array();

        foreach ($roles_02 as $value){  
            
            if($value["type"] == "rolecat"){
                array_push($array03, $value);
            }
        }
        foreach ($array02 as $value){  

        
            $logfile->logfile_writeline("getting here step 04");
            $sig = $value["sig"];
            $logfile->logfile_writeline(",,,,,,,,,,,,,,,,,,,,,,, the target sig is ,,,,,,,,,,,".$target_role_sig_decoded);
            $logfile->logfile_writeline(",,,,,,,,,,,,,,,,,,,,,,, the sig is ,,,,,,,,,,,".$sig);
            $role_scope_user = "SELECT scope_role_sig FROM acc_man_role_scope WHERE prop_id = ? and target_role_sig = ? and scope_role_sig = ?";
            $logfile->logfile_writeline("the select string::  ".$role_scope_user);
            $prepare = $role_scope_temp = $conn->prepare($role_scope_user);
            if($prepare){
                $logfile->logfile_writeline("prepare success");
                $bind = $role_scope_temp->bind_param("iss",$prop_id, $target_role_sig_decoded, $sig);
                if($bind){
                    $logfile->logfile_writeline("bind success");
                    $execute = $role_scope_temp->execute();
                    if($execute){
                        $logfile->logfile_writeline("execute success");
                        $role_scp_result = $role_scope_temp->get_result();
                        if($role_scp_result){
                            $logfile->logfile_writeline("result success");
                            $num_rows = mysqli_num_rows($role_scp_result);
                            $logfile->logfile_writeline("the number of rows are ::  ".$num_rows);
                        }else{
                            $logfile->logfile_writeline("result failure");
                        }
                        
                        if($num_rows == 0){
                            array_push($array03, $value);
                        }
                    }else{
                        $logfile->logfile_writeline("execute failure");
                    }
                    
                }else{
                    $logfile->logfile_writeline("bind failure");
                }
                
            }else{
                $logfile->logfile_writeline("prepare failure");
            }
            
            /*
            $roles03_r = get_role_by_sig_local($sig, $roles_03, $logfile);
            if($roles03_r["editable"] == "false"){
                array_push($array02, $roles03_r);
            }*/
        }
        $logfile->logfile_writeline("step 4 ends");
        // step 3.1.2.4. ends here:: step 04

        // step 3.1.2.5. starts here:: step 05
            //// To Be Discussed
        // step 3.1.2.5. ends here:: step 05
        

        // step 3.1.2.6. starts here:: step 06
        $sec_map_array =  array();

        
        $logfile->logfile_writeline("88888888 ::  ".$root_role_sig);

        $role_tree = get_role_tree($root_role_sig, $array03, $sec_map_array, $logfile);
        sec_push_map ("role_sig_role_scope_map", $sec_map_array);

        $curr_map = sec_get_map("role_sig_role_scope_map");
        $logfile->logfile_writeline(__FILE__."66666666666666 Dumping role_sig_role_scope_map 01  MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
        $logfile->logfile_writeline(__FILE__."66666666666666 Dumping role_sig_role_scope_map 01 MAP: End");

        

        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT_02";
        $tree_root_obj["text"] = "Prop Name";
        $obj = array(
            'ret_code'=>0,
            'root'=>$tree_root_obj,
            'role'=>$role_tree
        );
        $obj_json = json_encode($obj);
        $logfile->logfile_writeline("RoleTree:BEGIN"); 
        $logfile->logfile_writeline($obj_json); 
        $logfile->logfile_writeline("RoleTree:END"); 
        echo $obj_json;
        // step 3.1.2.6. ends here:: step 06
}
    
}
$logfile->logfile_close();
?>