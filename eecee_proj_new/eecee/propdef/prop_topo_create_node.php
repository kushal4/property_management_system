<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside get_prop_topo.php";

if (is_ajax()) {
    
    $myArray = [];
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $parent_id="";
    $node_id="";
    $node_name="";
    $unit="";

    foreach ($json_decoded as $key => $value) {
        //echo $key. "=>>>>>" .$value;
        //$log_str.="\n $key =>>>>> $value \n";
        if ($key=="parent_id"){
            $parent_id = $value;
        }
        if ($key=="node_id"){
            $node_id = $value;
        }
        if ($key=="node_name"){
            $node_name = $value;
        }
        if ($key=="unit"){
            $unit = $value;
        }
    }

    //echo "the parent ID is:: ".$parent_id."\n";
    //echo "the node ID is:: ".$node_id."\n";
    //echo "the node name is:: ".$node_name."\n";
   // echo "the unit is:: ".$unit."\n";
    
    $session_val= is_session_valid();

    if($session_val==0){
        $prop_id = $SENSESSION->get_val("prop_id");
        echo "the property ID is:: ".$prop_id;
        $parent_id_mapped = sec_get_map_val ("prop_topo_map", $parent_id);
        $logfile->logfile_writeline("the encoded parent ID is".$parent_id);
        $logfile->logfile_writeline("the decoded parent ID is".$parent_id_mapped);
        echo "the decoded parent ID is::".$parent_id_mapped;
        if ($parent_id != "#") {
            $sql_prop_topo = "INSERT INTO prop_topo(prop_id, node_name, parent_id, unit) VALUES (?, ?, ?, ?)";
            $sql_prop_topo_temp = $conn->prepare($sql_prop_topo);
            $sql_prop_topo_temp->bind_param("isii", $prop_id, $node_name, $parent_id_mapped, $unit);
            $sql_prop_topo_temp->execute();
            $last_id_prop_topo = $conn->insert_id;
            //Sec map this node ID
            
            $prop_topo_map = sec_get_map ("prop_topo_map"); //get the topo sec_map table from session
            sec_clear_map ("prop_topo_map"); //clear the sec_map table from session
            $rand_id = gen_unique_sec_id($prop_topo_map); //generate an unique random ID w.r.t prop sec_map table
            $prop_topo_map[$rand_id] = $last_id_prop_topo; //enter the new node's sec_map into the table
            sec_push_map ("prop_topo_map", $prop_topo_map); //push the modified sec_map table into session

            $logfile->logfile_writeline(__FILE__."---Dumping topo_list sec_map: Begin");
            foreach($prop_topo_map as $key => $val)
            {
                $logfile->logfile_writeline($key." : ".$val);
            }
            $logfile->logfile_writeline(__FILE__."---Dumping topo_list sec_map: End");

            $raw_json["last_id"] = $rand_id; //send encoded node_id to browser
        } else {
            $raw_json["last_id"] = $node_id;
        }
        $raw_json["ret_code"] = 0;
        $raw_json["temp_id"] = $node_id;

        $raw_json_encoce=json_encode($raw_json);
        echo $raw_json_encoce;
    }


    else{      
    }
    $conn->close();
}
$logfile->logfile_close();
?>