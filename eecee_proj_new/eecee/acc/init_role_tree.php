<?php
//session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';
require_once $sense_common_php_lib_path.'net.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}



function get_role_tree($role_sig, $role_arr, &$sec_map_arr, $logfile){
    $rand_id = gen_unique_sec_id($sec_map_arr); 
    $sec_map_arr[$rand_id]=$role_sig;

    $role_tree_obj = array();
    $role_tree_obj["children"] = array();

    $curr_role = get_role_by_sig($role_sig, $role_arr, $logfile);
    $role_tree_obj["id"] = $rand_id; //sec-mapped node_id
    $role_tree_obj["text"] = $curr_role["name"];
    //$role_tree_obj["reserved"] = $curr_role["reserved"];
    
    if ($curr_role["type"]=="rolecat") {
        $logfile->logfile_writeline(""); 
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
            //$role_tree_obj["type"] = "role_link";
        }

    }

    if ($curr_role["type"]=="linkedrole") {
        $role_tree_obj["type"] = "role_link";
    }



/*    
    if($curr_role["decommissioned"] == NULL || $curr_role["decommissioned"] == 0){
        $role_tree_obj["decommissioned"] = 0;
        $role_tree_obj["type"] = $curr_role["type"];
    }else{
        $role_tree_obj["decommissioned"] = $curr_role["decommissioned"];
        $role_tree_obj["type"] = "role_dcm";
    }
*/
/*
    if($curr_role["linked_sig"] != NULL){
        $role_tree_obj["type"] = "role_link";
        $copied_node_sig = $curr_role["linked_sig"];
        
        foreach ($role_arr as $value){  
            $Signature = $value["sig"];
            $Name = $value["name"];
            if($Signature == $curr_role["linked_sig"]){
                $role_tree_obj["text"] = $Name;
            }
        }
             
    }
*/

/*
    if($curr_role["reserved"] == 1 && $curr_role["type"] == "role"){
        $role_tree_obj["type"] = "role_res";
        $role_tree_obj["text"] = $curr_role["name"];
    }else if($curr_role["reserved"] == 1 && $curr_role["type"] == "rolecat"){
        $role_tree_obj["type"] = "cat_res";
        $role_tree_obj["text"] = $curr_role["name"];
    }
    $role_tree_obj["reserved"] = $curr_role["reserved"];
*/    


    
    
/*
    if(($curr_role["decommissioned"] == NULL || $curr_role["decommissioned"] == 0) && $curr_role["reserved"] == 1){
        $role_tree_obj["type"] = "role_cat_res";
    }else if(($curr_role["decommissioned"] == NULL || $curr_role["decommissioned"] == 0) && $curr_role["reserved"] == 0){
        $role_tree_obj["decommissioned"] = 0;
        $role_tree_obj["type"] = $curr_role["type"];
    }else{
        $role_tree_obj["decommissioned"] = $curr_role["decommissioned"];
        $role_tree_obj["type"] = "role_dcm";
    }
    */

    


    
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

    $logfile->logfile_writeline("---------------------------------*****************************************-----------------------------------------"); 

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $role_sig = $SENSESSION->get_val("role_sig");
    $prop_id = $SENSESSION->get_val("prop_id");
    $feat=$SENSESSION->get_val("FEA");
    $logfile->logfile_writeline("feat vagrtrl ::: ".$feat); 
   // $logfile->logfile_writeline("the role sig is::  ".$role_sig); 

    //$actlGetRoleDetsCurlURL = $actl_urls->actlGetRoleDetsCurlURL;
    $actlGetRoleTreeCurlURL = $actl_urls->actlGetRoleTreeCurlURL;
    
    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "role_sig"=>$role_sig,"hierarchy"=>"true"));



    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    //echo "data_str=".$data_str."<br>";
    $logfile->logfile_writeline("testing curl string : ".$data_str); 
    $actlRetObj = CurlSendPostJson($actlGetRoleTreeCurlURL, $data_str, $logfile); //what we get from the ACTL
    //
    
    $decodedJson = json_decode($actlRetObj, true);
    $jsonDataSTR = $decodedJson['d']."\n";
    $data_json_decode = json_decode($jsonDataSTR, true);
    $perm_array = $data_json_decode["p"];
    $SENSESSION->token("perm_obj", $perm_array);
    $roles = $perm_array["roles"];
    //print_r($roles);
    //$root = "#";
    $sec_map_array =  array();

    $logfile->logfile_writeline("session(FEA)". $feat); 
    if($feat=="ACC_MAN_ROLE"){
        $jstree_root_node_sig = $root_role_sig;
    }else{
        $jstree_root_node_sig = $swfm_root_role_sig;
     }

    $role_tree = get_role_tree($jstree_root_node_sig, $roles, $sec_map_array, $logfile);
    sec_push_map ("role_sig_map", $sec_map_array);

    $curr_map = sec_get_map("role_sig_map");
    $logfile->logfile_writeline(__FILE__."init_role_tree ********************************* Dumping role_sig_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."init_role_tree ********************************* Dumping role_sig_map MAP: End");

    //print_r($role_tree);

    $tree_root_obj = array();
    $tree_root_obj["children"] = array();
    $tree_root_obj["id"] = "PROP_ROOT";
    $tree_root_obj["text"] = "Prop Name";
  
    //print_r($actlRetObj);


    
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
    
    
}
$logfile->logfile_close();
?>