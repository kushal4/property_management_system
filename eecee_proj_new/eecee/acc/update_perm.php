<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'sec.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside delete role category";
if (is_ajax()) {
   
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $upd_perm_arr="";
    
    foreach ($json_decoded as $key => $value) {      
        if ($key=="upd_perm_arr"){
            $upd_perm_arr = $value;
        }
    }
    //echo $node_id;

    $arr_of_obj = array();

    foreach ($upd_perm_arr as $v){
        $sig = $v["sig"];
        $active = $v["active"];
        $decoded_sig = sec_get_map_val ("feat_sig_map", $sig);
        //$logfile->logfile_writeline("the encoded node ID is".$sig);
        //$logfile->logfile_writeline("the decoded node ID is".$decoded_sig);
        //echo "the encoded sig is::".$sig."\n";
        //echo "the decoded sig is::".$decoded_sig."\n";
        $field_data["sig"]=$value;

        $arr_of_sub_obj["sig"] = $decoded_sig;
        $arr_of_sub_obj["active"] = $active; 

        //echo "the sig is"
        
        array_push($arr_of_obj,$arr_of_sub_obj);
    }

    //print_r($arr_of_obj);

    //echo "\n";

    $arr_of_obj_encode=$arr_of_obj;

    

    /*
    $curr_map = sec_get_map("role_sig_map");
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map MAP: End");
    */
    //echo "the node SIG is:: ".$node_id_mapped."\n";

    
    $role_sig = $SENSESSION->get_val("role_sig");
    $prop_id = $SENSESSION->get_val("prop_id");
    $target_role_sig = $SENSESSION->get_val("target_role_sig");

    $logfile->logfile_writeline("################the target role sig is::##########################".$target_role_sig); 
    $logfile->logfile_writeline("################the logged role sig is::##########################".$role_sig); 
    
     
    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "logged_in_role_sig"=>$role_sig, "target_role_sig"=>$target_role_sig, "sigarr"=>$arr_of_obj_encode));
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $logfile->logfile_writeline("************************************************************");
    $logfile->logfile_writeline("To CURL".$data_str);

    //print_r($data);

    //echo "\n";
    
    $actlRetObj = CurlSendPostJson($actlSetPermCurlURL, $data_str); //what we get from the ACTL
    //$logfile->logfile_writeline("From CURL".$actlRetObj);

    $logfile->logfile_writeline("from curl".$actlRetObj); 
    $logfile->logfile_writeline("************************************************************");

    $perm_data_json_decode = json_decode($actlRetObj, true);
    $data_value = $perm_data_json_decode["d"];
    

    $data_json_decode = json_decode($data_value, true);
    $status_value = $data_json_decode["status"];
    $message = $data_json_decode["message"];
    $logfile->logfile_writeline("status_value=".$status_value); 
   

    if($status_value == 0){
        $raw_json["ret_code"] = 0;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }else{
        $raw_json["ret_code"] = 1;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }

   
    
}
$logfile->logfile_close();
?>