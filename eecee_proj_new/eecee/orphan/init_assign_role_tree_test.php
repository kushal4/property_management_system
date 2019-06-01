<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
include 'lib/php-lib/common_functions.php';
include '../lib/php-lib/sec.php';
include '../lib/php-lib/actl_lib.php';
include '../lib/php-lib/dom_func.php';
require '../lib/php-lib/composite_control_classes.php';
//include 'lib/php-lib/eecee_sec_map.php';
//include 'curl_url_include.php';

include '../lib/php-lib/session_exp.php';


$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function find_roles_by_parent($role_arr, $parent_sig){
    $child_arr = array();
    foreach ($role_arr as $r){  
        if($r["parent"] == $parent_sig){
            array_push($child_arr, $r);
        }   
    }
    return $child_arr;
}

function get_role_tree($role_sig, $role_arr, &$sec_map_arr, $logfile){
    $rand_id = gen_unique_sec_id($sec_map_arr); 
    $sec_map_arr[$rand_id]=$role_sig;
    $role_tree_obj = array();
    $role_tree_obj["children"] = array();
    $curr_role = get_role_by_sig($role_sig, $role_arr, $logfile);
    $role_tree_obj["id"] = $rand_id; //sec-mapped node_id
    $role_tree_obj["text"] = $curr_role["name"];
    $state_arr = array();
    if (array_key_exists("checked",$curr_role)){
        $state_arr["selected"] = $curr_role["checked"];
    }
    if (array_key_exists("disabled",$curr_role)){
        $state_arr["disabled"] = $curr_role["disabled"];
        $role_tree_obj["type"] = "role_dis";   
    }
    $role_tree_obj["state"]=$state_arr;
    $role_tree_obj["class"] = "sel_prop_class";
    if ($curr_role["type"]=="rolecat") {
        $role_tree_obj["class"] = "rolecat_no_check";
        $role_tree_obj["type"] = "rolecat";
        $role_tree_obj["data"] = "rolecat";
        if ($curr_role["reserved"]==1) {
            $role_tree_obj["type"] = "cat_res";
        }
    }
    if ($curr_role["type"]=="role") {
        $role_tree_obj["type"] = "role";
        $role_tree_obj["data"] = "role";
        if ($curr_role["reserved"]==1) {
            $role_tree_obj["type"] = "role_res";
        }
        if ($curr_role["decommissioned"]==1) {
            $role_tree_obj["type"] = "role_dcm";
        }

        if ($curr_role["linked_sig"]!=NULL) {
            $role_tree_obj["type"] = "role_link";
        }
    }
    if ($curr_role["type"]=="linkedrole") {
        $role_tree_obj["type"] = "role_link";
    }
    $children = get_role_child($role_sig, $role_arr, $logfile); //Find children
    foreach ($children as $child){  
        $child_sig = $child["sig"];
        $ret_child =  get_role_tree($child_sig, $role_arr, $sec_map_arr, $logfile);
        array_push($role_tree_obj["children"], $ret_child);
    }
   return $role_tree_obj;
}


//if (is_ajax()) {
    
    $prop_id = 46;
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    /*
    $raw_json_str = $_POST["k"];

    echo("AJAX Parameter:BEGIN"); 
    echo($raw_json_str); 
    echo("AJAX Parameter:END"); 

    $json_decoded = json_decode($raw_json_str, true);
    $user_id=""; 
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="user_id"){
            $user_id = $value;
        }
        
    }
    */
    $user_id = "g3VJYktMe0rwDa63";
    if($user_id == "0"){
        echo("=====================getting inside this================== user Id is 0");
        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = "Please select an user name from the first fold";
        $role_tree = array();
        $obj = array(
            'ret_code'=>0,
            'root'=>$tree_root_obj,
            'role'=>$role_tree 
        );
        $obj_json = json_encode($obj);
        echo("RoleTree:BEGIN"); 
        echo($obj_json); 
        echo("RoleTree:END"); 
        echo $obj_json;

    }else{
        echo("=====================getting inside this================== user Id is not 0");
        $logged_in_role_sig = "JG12A0T5wzblDPBl8snp4Y0DDK91QyAP";
        $prop_id = 46;

        // step 01 : starts here
        //$user_id_decoded = sec_get_map_val ("user_id_map", $user_id);
        $user_id_decoded = 43;
        echo("the encoded user ID is".$user_id);
        echo("the decoded user ID is".$user_id_decoded);
        
        $roles1 = array();
        $test_empty_role_01 = array();
        // step 01 : ends here

        // step 02 :: starts here (Get explicit scope roles for the logged in role)//
        $check_role_scp = "SELECT scope_role_sig FROM acc_man_role_scope WHERE prop_id = ? AND target_role_sig = ?";
        $role_scp_result_temp = $conn->prepare($check_role_scp);
        if($role_scp_result_temp){
            echo("prepare is successful");
            $bind = $role_scp_result_temp->bind_param("is",$prop_id, $logged_in_role_sig);
            if($bind){
                echo("bind is successful");
                $execute = $role_scp_result_temp->execute();
                if($execute){
                    echo("execute is successful");
                    $check_role_scp_result = $role_scp_result_temp->get_result();
                    $scope_roles_array = $check_role_scp_result->fetch_all(); // scope_role_array
                    $num_rows = mysqli_num_rows($check_role_scp_result);
                    echo("refresh_scope_list -------- here step 02");
                    echo("the number of row is");
                }else{
                    echo("execute is failure");
                }
                
            }else{
                echo("bind is failure");
            }
            
        }else{
            echo("prepare is failure");
        }
        


        if($num_rows == 0){

        }else{
            $sig_arr_roles = array();
            foreach ($scope_roles_array as $value) {
                $role_scope_db_sig = $value[0];
                echo(" role_scope_db_sig=".$role_scope_db_sig);
                array_push($sig_arr_roles,$role_scope_db_sig);
            } 
    
            $projectKey = $projectID;
            $hierarchy_false = "false";
    
            $data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id, "sig_arr"=>$sig_arr_roles, "hierarchy"=>$hierarchy_false));
            $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
            $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL
            $decodedJson = json_decode($actlRetObj, true);
            $jsonDataSTR = $decodedJson['d']."\n";
            $data_json_decode = json_decode($jsonDataSTR, true);
            $perm_array = $data_json_decode["p"];
            $api_scope_role_details = $perm_array["roles"];

            $api_scope_role_details_str = var_export($api_scope_role_details, true);
            echo("step 2 :: api_scope_role_details array is ::: ".$api_scope_role_details_str);

            foreach($api_scope_role_details as $r){
                if($r["type"] == "role"){
                    $r["disabled"] = false;
                    $r["checked"] = false;
                    array_push($roles1, $r);//pushed role category into $roles1
                }
            }
    

        }
        // step 02 :: ends here //


        // Step 03 :: starts here (Get all the descendant roles of the logged in role)//
            
        $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "role_sig"=>$logged_in_role_sig));
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
        $actlRetObj = CurlSendPostJson($actlGetRoleTreeCurlURL, $data_str, $logfile); //what we get from the ACTL
        $decodedJson = json_decode($actlRetObj, true);
        $jsonDataSTR = $decodedJson['d']."\n";
        $data_json_decode = json_decode($jsonDataSTR, true);
        $perm_array = $data_json_decode["p"];
        //$_SESSION["perm_obj"] = $perm_array;
        $api_logged_role_tree = $perm_array["roles"];

        ////// Tree creation ::: endss here
        // Step 03 :: ends here //

        // Step 04 :: starts here 
        /*
        foreach ($api_logged_role_tree as $entry) {
            if ($entry["type"] == "rolecat") {
                //add a has_child property
                //$entry["has_child"] = false;
                array_push($roles1, $entry);
            }
        }*/


        foreach ($api_logged_role_tree as $value){  
            if (array_key_exists("editable",$value))
            {
                if($value["editable"] == true){
                    $value["disabled"] = false;
                    $value["checked"] = false;
                    array_push($roles1, $value);
                }
            }
        }

        ////// Tree creation ::: starts here
        /*
        $sec_map_array =  array();
        $role_tree = get_role_tree($root_role_sig, $roles1, $sec_map_array, $logfile);
        sec_push_map ("assign_role_sig_map", $sec_map_array);

        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = "Please select an user name from the first fold";
        $obj = array(
            'ret_code'=>0,
            'root'=>$tree_root_obj,
            'role'=>$role_tree 
        );
        $obj_json = json_encode($obj);
        echo("RoleTree:BEGIN"); 
        echo($obj_json); 
        echo("RoleTree:END"); 
        echo $obj_json;

        */

        ////// Tree creation ::: ends here
        // Step 04 :: ends here


        // Step 05 :: starts here
        echo("Step 05 ::: BEGIN "); 
        echo("the user ID is ::: ".$user_id_decoded); 
        echo("the prop ID is ::: ".$prop_id); 

        $user_role_sql = "SELECT * FROM context_role WHERE prop_id = ? AND user_id = ?";
        $user_role_stmt = $conn->prepare($user_role_sql);
        $user_role_stmt->bind_param("ii",$prop_id, $user_id_decoded);
        $user_role_stmt->execute();
        $user_role_result = $user_role_stmt->get_result();
        $user_role_row = $user_role_result->fetch_all();
        $number_of_row = mysqli_num_rows($user_role_result);
        echo("the number of row is:: ".$number_of_row); 
        echo("Step 05 ::: END "); 

        /*
        foreach ($api_logged_role_tree as $entry) {
            if ($entry["type"] == "rolecat") {
                //add a has_child property
                //$entry["has_child"] = false;
                array_push($roles1, $entry);
            }
        }

        $sec_map_array =  array();
        $role_tree = get_role_tree($root_role_sig, $roles1, $sec_map_array, $logfile);
        sec_push_map ("assign_role_sig_map", $sec_map_array);

        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = "Please select an user name from the first fold";
        $obj = array(
            'ret_code'=>0,
            'root'=>$tree_root_obj,
            'role'=>$role_tree 
        );
        $obj_json = json_encode($obj);
        echo("RoleTree:BEGIN"); 
        echo($obj_json); 
        echo("RoleTree:END"); 
        echo $obj_json;
        */
        // Step 05 :: ends here

        // Step 06 :: Starts here
        echo("Step 06 ::: BEGIN "); 
        $has_user_role = true;
        if($number_of_row == 0){
            $has_user_role = false;
        }else{
            $user_role_array = array();
            foreach ($user_role_row as $value){  
                $role_sig = $value["3"];
                array_push($user_role_array,$role_sig); 
            }

            $projectKey = $projectID;
            $hierarchy_true = "true";

            $data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id, "sig_arr"=>$user_role_array, "hierarchy"=>$hierarchy_true));
            $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
            $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL
            $decodedJson = json_decode($actlRetObj, true);
            $jsonDataSTR = $decodedJson['d']."\n";
            $data_json_decode = json_decode($jsonDataSTR, true);
            $perm_array = $data_json_decode["p"];
            $api_user_role_tree_roles = $perm_array["roles"]; 
            $api_user_role_tree_tree = $perm_array["Tree"]; //$api_user_role_tree->tree
    
            $api_user_role_tree_tree_str = var_export($api_user_role_tree_tree, true);
            echo("step 6 :: tree array from role_dets is ::: ".$api_user_role_tree_tree_str);
        }
        echo("Step 06 ::: END "); 
        /*
        
        foreach ($api_logged_role_tree as $entry) {
            if ($entry["type"] == "rolecat") {
                //add a has_child property
                //$entry["has_child"] = false;
                array_push($roles1, $entry);
            }
        }

        $sec_map_array =  array();
        $role_tree = get_role_tree($root_role_sig, $roles1, $sec_map_array, $logfile);
        sec_push_map ("assign_role_sig_map", $sec_map_array);

        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = "Please select an user name from the first fold";
        $obj = array(
            'ret_code'=>0,
            'root'=>$tree_root_obj,
            'role'=>$role_tree 
        );
        $obj_json = json_encode($obj);
        echo("RoleTree:BEGIN"); 
        echo($obj_json); 
        echo("RoleTree:END"); 
        echo $obj_json;
        */       

        // Step 06 :: Ends here


        // Step 07 :: Starts here
        if ($has_user_role==true) {
        
            echo ("step 7 :: Checking roles :: BEGIN");
            $roles1_str = var_export($roles1, true);
            echo ("step 7 :: roles1 is ::: ");
            var_dump ($roles1_str);

            
            foreach($api_user_role_tree_tree as $key=>$value){
            foreach($value as $r){
                
                    echo ("Searching role name ". $r["sig"]);
                    try {
                    $f = &get_role_ref_by_sig($r["sig"], $roles1, $logfile);
                    }
                    catch (Exception $e) {
                        echo ("step 7 :: exception is ::: ".$e->getMessage());
                    }
                    
                    if ($f != NULL) {
                        $f_str = var_export($f, true);        
                        echo ("f found:: ". $f_str);

                        $f["disabled"] = true;
                        $f["checked"] = true;

                        $f_str = var_export($f, true);        
                        echo ("f found:: final". $f_str);
                    } else {
                        echo ("f is NULL");
                    }
                
                }
            }
            

            echo("step 7 :: Checking roles :: END");
            $roles1_str = var_export($roles1, true);
            echo("step 7 :: roles1 is ::: ".$roles1_str);


            foreach ($api_logged_role_tree as $entry) {
                if ($entry["type"] == "rolecat") {
                    //add a has_child property
                    //$entry["has_child"] = false;
                    array_push($roles1, $entry);
                }
            }

            $sec_map_array =  array();
            $role_tree = get_role_tree($root_role_sig, $roles1, $sec_map_array, $logfile);
            sec_push_map ("assign_role_sig_map", $sec_map_array);

            $tree_root_obj = array();
            $tree_root_obj["children"] = array();
            $tree_root_obj["id"] = "PROP_ROOT";
            $tree_root_obj["text"] = "Please select an user name from the first fold";
            $obj = array(
                'ret_code'=>0,
                'root'=>$tree_root_obj,
                'role'=>$role_tree 
            );
            $obj_json = json_encode($obj);
            echo("RoleTree:BEGIN"); 
            echo($obj_json); 
            echo("RoleTree:END"); 
            echo $obj_json;

        }
            // Step 07 :: Ends here
        /*
        // Step 08 :: Starts here 
        if ($has_user_role==true) {
            foreach ($api_user_role_tree_tree as $key=>$value){  
                echo("step 8 :: the key is::".$key);
                
                    $f = &get_role_ref_by_sig($key, $roles1, $logfile);
                    if ($f != NULL) {
                        
                        $f["checked"] = true;
                    } 
                
            }
            $roles1_str = var_export($roles1, true);
            echo("step 8 :: roles1 is ::: ".$roles1_str);

            
        }
        // Step 08 :: Ends here

        // Step 09 :: Starts here
        foreach ($api_logged_role_tree as $entry) {
            if ($entry["type"] == "rolecat") {
                //add a has_child property
                $entry["has_child"] = false;
                array_push($roles1, $entry);
            }
        }
        
        $sec_map_array =  array();
        $role_tree = get_role_tree($root_role_sig, $roles1, $sec_map_array, $logfile);
        sec_push_map ("assign_role_sig_map", $sec_map_array);

        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = "Please select an user name from the first fold";
        $obj = array(
            'ret_code'=>0,
            'root'=>$tree_root_obj,
            'role'=>$role_tree 
        );
        $obj_json = json_encode($obj);
        echo("RoleTree:BEGIN"); 
        echo($obj_json); 
        echo("RoleTree:END"); 
        echo $obj_json;

        // Step 09 :: Ends here
        */
        


        }


//}
    

    

    

$logfile->logfile_close();
?>
