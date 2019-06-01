<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
include '../prop_topo.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_sec_map.php';
include $eecee_php_lib_path.'eecee_lib.php';
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
    
    
    $session_val= is_session_valid();

    if($session_val==0){
        
        $prop_id = $SENSESSION->get_val("prop_id");
        $user_id = $SENSESSION->get_val("user_id");
        $logfile->logfile_writeline("***********the user ID is************::".$user_id);
        $logfile->logfile_writeline("***********the property ID is************::".$prop_id);
        //echo "prop name".$prop_id;
        $parentid = 0;

        $sql_check_prop = "SELECT * FROM prop_topo WHERE prop_id = ? AND parent_id = ? ";
        $sql_check_prop_temp = $conn->prepare($sql_check_prop);
        $sql_check_prop_temp->bind_param("ii",$prop_id, $parentid);
        $sql_check_prop_temp->execute();
        $sql_check_prop_result = $sql_check_prop_temp->get_result();
        $sql_check_prop_row = $sql_check_prop_result->fetch_assoc();
        $prop_topo_root_id = $sql_check_prop_row["id"];

        $sql_check_prop_row_str = var_export($sql_check_prop_row, true);
        $logfile->logfile_writeline("sql_check_prop_row_str :: ".$sql_check_prop_row_str); 

        
        $sec_map_array =  array();
        $topo_tree = get_topo_tree($prop_topo_root_id, $conn, $prop_id, $logfile, $sec_map_array, $user_id);
        sec_push_map ("prop_topo_map", $sec_map_array); 

        $curr_map = sec_get_map("prop_topo_map");

        $logfile->logfile_writeline(__FILE__."------------------------------------------------Dumping prop_topo_map MAP: Begin");
                foreach($curr_map as $key => $value)
                    {
                        $logfile->logfile_writeline($key." : ".$value);
                    }
        $logfile->logfile_writeline(__FILE__."------------------------------------------------Dumping prop_topo_map MAP: End");




        $logfile->logfile_writeline(__FILE__."---Dumping topo_list sec_map: Begin");
        foreach($sec_map_array as $key => $val)
        {
            $logfile->logfile_writeline($key." : ".$val);
        }
        $logfile->logfile_writeline(__FILE__."---Dumping topo_list sec_map: End");
    
        //$topo_tree["li_attr"] = array("class"=>"topo_root", "data-jstree"=>"{ \"type\" : \"group\" }");
        $topo_tree["li_attr"] = array("class"=>"topo_root");
        $topo_tree["a_attr"] = array("class"=>"topo_root");
        //array_push($topo_tree["li_attr"], array("class"=>"topo_root"));
        //$logfile->logfile_writeline("get_prop_topo::AJAX:: topo_tree ".$topo_tree);
        //$vd_str = var_export($topo_tree, true);
        //$logfile->logfile_writeline("get_prop_topo::AJAX:: topo_tree ".$vd_str);
        //$logfile->logfile_writeline("get_prop_topo::AJAX:: topo_tree ");


        //print_r ($sql_check_prop_row);
        /*
        foreach ($sql_check_prop_row as $value){  
            $prop_name = $sql_check_prop_row["setup_name"];
        }*/
        //echo "the property name is".$prop_name;
        $sql_prop_name = "SELECT * FROM properties WHERE id = ? ";
        $sql_prop_name_temp = $conn->prepare($sql_prop_name);
        $sql_prop_name_temp->bind_param("i",$prop_id);
        $sql_prop_name_temp->execute();
        $sql_prop_name_result = $sql_prop_name_temp->get_result();
        $sql_prop_name_result_row = $sql_prop_name_result->fetch_assoc();
        //print_r ($sql_prop_name_result_row);

        //foreach ($sql_prop_name_result_row as $value){  
            $prop_name = $sql_prop_name_result_row["setup_name"];
            $logfile->logfile_writeline(__FILE__." the property name is:: ".$prop_name);
            //$user_id = $sql_prop_name_result_row["created_by"];
        //}
        //$ret_code["ret_code"] = 0;

        $topo_tree["text"] = $prop_name;
        //$topo_tree["user_id"] = $user_id;
        $obj = array(
            'ret_code'=>0,
            'topo'=>$topo_tree
            
        );
        
        $obj_json = json_encode($obj);
        echo $obj_json;
        
    }

    else{      
    }
}
$logfile->logfile_close();
?>