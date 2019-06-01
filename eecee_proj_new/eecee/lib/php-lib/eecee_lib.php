<?php 

include '../eecee_include.php';
require_once $sense_common_php_lib_path.'net.php';
function find_property_name($prop_id){
    $user_role_sql = "SELECT * FROM properties WHERE id = ?";
    $user_role_stmt = $conn->prepare($user_role_sql);
    $user_role_stmt->bind_param("i",$prop_id);
    $user_role_stmt->execute();
    $user_role_result = $user_role_stmt->get_result();
    $user_role_row = $user_role_result->fetch_assoc();
    $property_name = $user_role_row["setup_name"];
    return $property_name;
}


function get_user_contexts($arg){
     
    $prop_id = $arg["prop_id"];
    $user_id = $arg["user_id"];
    $conn = $arg["dbconn"];
    $logfile = $arg["logfile"];
    //$logfile->logfile_writeline("getting inside get_user_contexts");
    $logfile->logfile_writeline("the prop ID is :: ".$prop_id);
    $logfile->logfile_writeline("the user ID is :: ".$user_id);
    //$logfile->logfile_writeline("the conn is :: ".$conn);

    //$conn_str = var_export($conn, true);
    //$logfile->logfile_writeline("the conn is :: ".$conn_str);

    $sql_contexts = "SELECT * FROM contexts WHERE prop_id = ? AND user_id = ?";
    $contexts_temp = $conn->prepare($sql_contexts);
    if($contexts_temp){
        //$logfile->logfile_writeline("prepare successful");
        $bind = $contexts_temp->bind_param("ii",$prop_id, $user_id);
        if($bind){
            //$logfile->logfile_writeline("bind successful");
            $execute = $contexts_temp->execute();
            if($execute){
                //$logfile->logfile_writeline("execute successful");
                $contexts_result = $contexts_temp->get_result(); //mysqli object
                $contexts_fetch_all = $contexts_result->fetch_all(MYSQLI_ASSOC);
            }else{
                //$logfile->logfile_writeline("execute failed");
            }
            
        }else{
            //$logfile->logfile_writeline("bind failed");
        }
        
    }else{
        //$logfile->logfile_writeline("prepare failed");
    }
    

    //$contexts_fetch_all_str = var_export($contexts_fetch_all, true);
    //$logfile->logfile_writeline("contexts_fetch_all_str :: ".$contexts_fetch_all_str);

    $context_array = array();
    foreach ($contexts_fetch_all as $value){ 
        array_push($context_array, $value);
    }
    return $context_array;
}

function get_context_roles($arg, &$context_array, $hierarchy){
    $logfile = $arg["logfile"];
    $conn = $arg["dbconn"];
    $prop_id = $arg["prop_id"];
    $user_id = $arg["user_id"];
    $projectID = $arg["proj_key"];
    $logfile->logfile_writeline("getting inside get_context_roles ");
    $actl_urls = $arg["actl_urls"];
    $actlGetRoleDetsCurlURL = $actl_urls->actlGetRoleDetsCurlURL;
    $logfile->logfile_writeline("the cURL name is :: ".$actlGetRoleDetsCurlURL);
    $actl_urls_str = var_export($actl_urls, true);
    $logfile->logfile_writeline("get_context_roles :: actl_urls :: ".$actl_urls_str);
    $sig_arr_roles = array();
    $context_array_str = var_export($context_array, true);
    $logfile->logfile_writeline("get_context_roles :: context_array :: ".$context_array_str);
    foreach ($context_array as $key=>&$value){ 
        //$value_str = var_export($value, true);
        //$logfile->logfile_writeline("get_context_roles ::inside foreach :: value".$value_str);
        $context_id = &$value["id"];
        $user_role_sql = "SELECT * FROM context_role WHERE ctx_id = ?";
        $user_role_stmt = $conn->prepare($user_role_sql);
        $user_role_stmt->bind_param("i",$context_id);
        $user_role_stmt->execute();
        $user_role_result = $user_role_stmt->get_result();
        $user_role_row = $user_role_result->fetch_all(MYSQLI_ASSOC);

        $user_role_row_str = var_export($user_role_row, true);
        $logfile->logfile_writeline("user_role_row_str :: ".$user_role_row_str);
        foreach ($user_role_row as $key=>$val){
            $role_sig = $val["role_sig"]; 
            array_push($sig_arr_roles, $role_sig);
        }
        //$sig_arr_roles_str = var_export($sig_arr_roles, true);
        //$logfile->logfile_writeline("get_context_roles :: sig_arr_roles_str :: ".$sig_arr_roles_str);

        $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "sig_arr"=>$sig_arr_roles, "hierarchy"=>$hierarchy));
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
        $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL
        $logfile->logfile_writeline("the curl response");
        print_r($actlRetObj, true);

        $logfile->logfile_writeline("the curl response :: after");
        $scope_role_details = json_decode($actlRetObj, true);
        $jsonDataSTR = $scope_role_details['d']."\n";
        $data_json_decode = json_decode($jsonDataSTR, true);
        $perm_array = $data_json_decode["p"];
        $scope_role_details_roles = $perm_array["roles"];

        //$scope_role_details_roles_str = var_export($scope_role_details_roles, true);
        //$logfile->logfile_writeline("get_context_roles :: scope_role_details_roles_str :: ".$scope_role_details_roles_str);
        $context_array_str = var_export($context_array, true);
        //$logfile->logfile_writeline("get_context_roles inside for each :: context_array :: ".$context_array_str);
        $value["role"] = $scope_role_details_roles;
        //$value_str = var_export($value, true);
        //$logfile->logfile_writeline("get_context_roles ::inside foreach :: value :: after adiing role".$value_str);
    }
    $context_array_str = var_export($context_array, true);
    $logfile->logfile_writeline("get_context_roles return :: context_array :: ".$context_array_str);
    return $context_array;

}

$char_set = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~`!@#$%^&*()_+-=[]\{}|;:,./<>?";

function obfuscate($target_str, $char_set, $key) {
    return strtr($target_str, $char_set, $key);
}

function deobfuscate($target_str, $char_set, $key) {
    return strtr($target_str, $key, $char_set);
}

function get_topo_tree($node_id, $db_conn, $prop_id, $log, &$sec_map_arr, $user_id) {
    
    $rand_id = gen_unique_sec_id($sec_map_arr); 
    
    $sec_map_arr[$rand_id]=$node_id;
    $topo_tree_obj = array();
    //print_r($node_array);
    //fwrite($fp, print_r($array, TRUE));
    $topo_tree_obj["children"] = array();
    //$log->logfile_writeline("get_prop_topo::get_topo_tree:: Prop ID=".$prop_id."; Node ID=".$node_id);
    //Find node's name
    $sql = "SELECT * FROM prop_topo WHERE prop_id = ? AND id = ? ";
    $sql_prep_stmt = $db_conn->prepare($sql);
    $sql_prep_stmt->bind_param("ii",$prop_id, $node_id);
    $sql_prep_stmt->execute();
    $sql_exec_result = $sql_prep_stmt->get_result();
    $row = $sql_exec_result->fetch_assoc();

    if($user_id == 8){
        $topo_tree_obj["ud"] = 1;
    }

    $topo_tree_obj["id"] = $rand_id; //sec-mapped node_id
    $topo_tree_obj["text"] = $row["node_name"];
    
    $topo_tree_obj["test"] = "test";
    


    if($row["unit"] == 0){
        $topo_tree_obj["type"] = "group";
        $topo_tree_obj["data"] = "group";
        
        //$topo_tree_obj["li_attr"] = array("data-jstree"=>"{ \"type\" : \"group\" });
    }else{
        $topo_tree_obj["type"] = "unit";
        $topo_tree_obj["data"] = "unit";
        
        //$topo_tree_obj["li_attr"] = array("data-jstree"=>"{ \"type\" : \"unit\" }");
    }

    //Find Children
    $sql = "SELECT * FROM prop_topo WHERE prop_id = ? AND parent_id = ? ";
    $sql_prep_stmt = $db_conn->prepare($sql);
    //$log->logfile_writeline("get_prop_topo::get_topo_tree:: Here 1=");
    $sql_prep_stmt->bind_param("ii",$prop_id, $node_id);
    //$log->logfile_writeline("get_prop_topo::get_topo_tree:: Here 2=");
    $sql_prep_stmt->execute();
    //$log->logfile_writeline("get_prop_topo::get_topo_tree:: Here 3=");
    $sql_exec_result = $sql_prep_stmt->get_result();
    //$log->logfile_writeline("get_prop_topo::get_topo_tree:: Here 4=");
    //$log->logfile_writeline("get_prop_topo::get_topo_tree:: Printing Children ". $sql_exec_result->num_rows);
    
    if ($sql_exec_result->num_rows > 0) {
        while ($row = $sql_exec_result->fetch_assoc())
        {
            $child_id = $row["id"];
            //$log->logfile_writeline("get_prop_topo::get_topo_tree:: Child ID=".$child_id);
            $ret_child =  get_topo_tree($child_id, $db_conn, $prop_id, $log, $sec_map_arr, $user_id);
            array_push($topo_tree_obj["children"], $ret_child);
        }
    }
    //$sql_prep_stmt->free(); unset($sql_prep_stmt);
    $sql_exec_result->free(); unset($sql_exec_result);
    //$log->logfile_writeline("get_prop_topo::get_topo_tree:: Returning from ".$node_id);
    
    return $topo_tree_obj;
    //return json_encode($topo_tree_obj);
    //return 0;
}
?>
