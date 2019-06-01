<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL); 
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

//include 'lib/php-lib/eecee_constants.php';
//include 'lib/php-lib/eecee_include.php';
include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'reg_func.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");


/*
foreach (array_keys($_SESSION) as $k) {
    $logfile->logfile_writeline(__FILE__."Session Key: ". $k);
}


if (key_exists("feat_list_tbl_sig_map", $_SESSION)) {
    $m = $_SESSION["feat_list_tbl_sig_map"];
    $logfile->logfile_writeline(__FILE__."Dumping : feat_list_tbl_sig_map: Begin");
    foreach (array_keys($m) as $k) {
        $logfile->logfile_writeline(__FILE__. $k . " = ".$m[$k]);
    }
    $logfile->logfile_writeline(__FILE__."Dumping : feat_list_tbl_sig_map: End");
}
*/




function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $feat_id=""; 
    $attr_id="";
    $attrib_value="";
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="feat_id"){
            $feat_id = $value;
        }

        if ($key=="attr_id"){
            $attr_id = $value;
        }

        if ($key=="attrib_value"){
            $attrib_value = $value;
        }
        
    }

    $curr_map = sec_get_map("feat_list_tbl_sig_map");
    $logfile->logfile_writeline(__FILE__."---Dumping feat_list_tbl_sig_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."---Dumping feat_list_tbl_sig_map MAP: End");


    $feat_id_decoded = sec_get_map_val ("feat_list_tbl_sig_map", $feat_id);
    $logfile->logfile_writeline("the encoded feature ID is".$feat_id);
    $logfile->logfile_writeline("the decoded feature ID is".$feat_id_decoded);

    $curr_map = sec_get_map("attrib_id_map");
    
    $logfile->logfile_writeline(__FILE__."---Dumping attrib_id_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."---Dumping attrib_id_map MAP: End");
    
    $attr_id_decoded = sec_get_map_val ("attrib_id_map", $attr_id);
    $logfile->logfile_writeline("the encoded attribute ID is".$attr_id);
    $logfile->logfile_writeline("the decoded attribute ID is".$attr_id_decoded);

    
    $unit_type_id = $SENSESSION->get_val("unit_type_id");
    $logfile->logfile_writeline("the unit_type ID is".$unit_type_id);
    $prop_id = $SENSESSION->get_val("prop_id");
    $logfile->logfile_writeline("the prop ID is".$prop_id);

    $sql_insrt="INSERT INTO unit_fea_attrib(unit_fea_id, attrib_id, unit_type_id) VALUES (?, ?, ?)";
        $sql_temp = $conn->prepare($sql_insrt);
        if($sql_temp){
            //echo "prepare successful"."\n";
            $logfile->logfile_writeline("prepare successful");
            $bind_temp = $sql_temp->bind_param("iii", $feat_id_decoded, $attr_id_decoded, $unit_type_id);
            if($bind_temp){
                //echo "bind successful \n";
                $logfile->logfile_writeline("bind successful");
                $exe_temp = $sql_temp->execute();
                if($exe_temp){
                   // echo "execution successful \n";
                   $logfile->logfile_writeline("execution successful");
                    $raw_json ["ret_code"] = 0;
                    $raw_json_encode = json_encode($raw_json);
                    echo $raw_json_encode;
                }else{
                    //echo "execution failed \n";
                    $logfile->logfile_writeline("execution failed");
                    $raw_json ["ret_code"] = 1;
                    $raw_json_encode = json_encode($raw_json);
                    echo $raw_json_encode;
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



?>