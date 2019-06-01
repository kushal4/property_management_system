<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";
require_once $sense_common_php_lib_path.'Log.php';

//include 'lib/php-lib/eecee_lib.php';
//include 'curl_url_include.php';
//$log_path = "Logs/eecee.log";
include $sense_common_php_lib_path.'session_exp.php';
//include 'sec.php';
//require_once 'Log.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside save_setup_prop.php";



if (is_ajax()) {
    
    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $setup_name="";
    //echo "the name is".$setup_name;
    $setup_add1="";
    $setup_add2="";
    $setup_locality="";
    $setup_city="";
    $setup_state="";
    $setup_country="";
    $setup_pincode="";
        foreach ($json_decoded as $key => $value) {
            //echo $key. "=>>>>>" .$value;
           // $log_str.="\n $key =>>>>> $value \n";
            if ($key=="setup_name"){
                $setup_name = $value;
                //echo "the name is".$setup_name;
            }
            if ($key=="setup_add1"){
                $setup_add1 = $value;
                //echo "the add line1 is".$setup_add1;
            }
            if ($key=="setup_add2"){
                $setup_add2 = $value;
               //echo "the add line2 is".$setup_add2;
            }
            if ($key=="setup_locality"){
                $setup_locality = $value;
               //echo "the locality is".$setup_locality;
            }
            if ($key=="setup_city"){
                $setup_city = $value;
               //echo "the city is".$setup_city;
            }
            if ($key=="setup_state"){
                $setup_state = $value;
                //echo "the state is".$setup_state;
            }
            if ($key=="setup_country"){
                $setup_country = $value;
               //echo "the country is".$setup_country;
            }
            if ($key=="setup_pincode"){
                $setup_pincode = $value;
                //echo "the pincode is".$setup_pincode;
            }
        }

        $conn = new \mysqli($server_name, $user_name, $password, $dbname);
        $userid = $SENSESSION->get_val("user_id");
        echo "the user ID is:: ".$userid."\n";
        
        $sql_insrt="INSERT INTO properties(setup_name, setup_add_line1, setup_add_line2, setup_locality, setup_city, setup_state, setup_country, setup_pincode, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $sql_temp = $conn->prepare($sql_insrt);
        if($sql_temp){
            //echo "prepare successful"."\n";
            $bind_temp = $sql_temp->bind_param("sssssssii", $setup_name, $setup_add1, $setup_add2, $setup_locality, $setup_city, $setup_state, $setup_country, $setup_pincode, $userid );
            if($bind_temp){
                //echo "bind successful \n";
                $exe_temp = $sql_temp->execute();
                if($exe_temp){
                   // echo "execution successful \n";
                }else{
                    //echo "execution failed \n";
                }

            }else{
                //echo "bind failed \n";
            }
        }else{
           // echo "prepare failed \n";
        }
        
        
        
        $last_id = $conn->insert_id;
        $SENSESSION->token("prop_id", $last_id);
        $last_id = $sql_temp->insert_id;
        //echo "the last ID is".$last_id;
        $userid = $SENSESSION->get_val("user_id");
        //echo "the user ID is::".$userid;

        $prop_topo_parent_0 = 0;
        $prop_topo_unit_0 = 0;
        $sql_prop_topo = "INSERT INTO prop_topo(prop_id, node_name, parent_id, unit) VALUES ( ?, ?, ?, ?)";
        $prop_topo_temp = $conn->prepare($sql_prop_topo);
        if($prop_topo_temp){
            //echo "prepare successful \n";
            $prop_topo_bind = $prop_topo_temp->bind_param("isii", $last_id, $setup_name, $prop_topo_parent_0, $prop_topo_unit_0);
            if($prop_topo_bind){
                //echo "bind sucessful \n";
                $prop_topo_exe = $prop_topo_temp->execute();
                if($prop_topo_exe){
                   // echo "execution successful \n";
                }else{
                    //echo "execution failed \n";
                }

            }else{
                //echo "bind failed \n";
            }
        }else{
            //echo "prepare failed \n";
        }


        $actlCreateClientCurlURL;

        $projectKey = $projectID;

        //echo "the project ID is:: ".$projectKey;
        
        $data=array("param"=>array("key"=>$projectID,"client_id"=>$last_id));
        
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
        $actlRetObj = CurlSendPostJson($actlCreateClientCurlURL, $data_str); //what we get from the ACTL
        $decodedJson = json_decode($actlRetObj, true);

        //echo $decodedJson;

        $jsonDataSTR = $decodedJson['d']."\n";
        $data_str = json_decode($jsonDataSTR, true);

        print_r($data_str["p"]);

        $perm_array = $data_str["p"];
       // echo $perm_array["role_cat_sig"]."\n";
        //echo $perm_array["role_sig"]."\n";
        $role_sig = $perm_array["role_sig"];
        
        
        $sql_sel_prop = "INSERT INTO context_role(user_id, prop_id, role_sig) VALUES ( ?, ?, ?)";
        $sql_sel_prop_temp = $conn->prepare($sql_sel_prop);
        if($sql_sel_prop_temp){
            //echo "prepare successful \n";
            $sql_bind = $sql_sel_prop_temp->bind_param("iis", $userid, $last_id, $role_sig);
            if($sql_bind){
                //echo "bind sucessful \n";
                $sql_exe = $sql_sel_prop_temp->execute();
                if($sql_exe){
                   // echo "execution successful \n";
                }else{
                    //echo "execution failed \n";
                }

            }else{
                //echo "bind failed \n";
            }
        }else{
            //echo "prepare failed \n";
        }
/////////////////////////////////////////////////


 ///////////////////////////////////////////////////////       

        $active_bit=1;
        $suspended_bit=0;
        $modcode = "new";

        $sql_sel_prop = "INSERT INTO contexts(user_id, prop_id, active, suspended, mod_code, mod_by) VALUES ( ?, ?, ?, ?, ?, ?)";
        $sql_sel_prop_temp = $conn->prepare($sql_sel_prop);
        if($sql_sel_prop_temp){
            //echo "prepare successful \n";
            $sql_bind = $sql_sel_prop_temp->bind_param("iiiisi", $userid, $last_id, $active_bit, $suspended_bit, $modcode, $userid );
            if($sql_bind){
                //echo "bind sucessful \n";
                $sql_exe = $sql_sel_prop_temp->execute();
                if($sql_exe){
                   // echo "execution successful \n";
                }else{
                    //echo "execution failed \n";
                }

            }else{
                //echo "bind failed \n";
            }
        }else{
            //echo "prepare failed \n";
        }
        

        

        $sql_prop_topo = "INSERT INTO prop_topo(id, parent_id) VALUES (?, ?)";
        $sql_prop_topo_temp = $conn->prepare($sql_prop_topo);
        $parent_id=0;
        $sql_prop_topo_temp->bind_param("ii", $last_id, $parent_id);
        $sql_prop_topo_temp->execute();
        $last_id_prop_topo = $conn->insert_id;
        //$sql_prop_topo_temp_result = $sql_prop_topo_temp->get_result();
        //$sql_check_prop_row = $sql_prop_topo_temp_result->fetch_assoc();
        //print_r($sql_check_prop_row);

        //$sql_prop_topo_update = "UPDATE prop_topo SET node_id=? WHERE id=?";
        //$sql_prop_topo_update_stmt=$conn->prepare($sql_prop_topo_update);
        //$sql_prop_topo_update_stmt->bind_param("ii", $last_id_prop_topo, $last_id_prop_topo);
        //$sql_prop_topo_update_stmt->execute();

        $raw_json["ret_code"] = 0;
        $raw_json["prop_name"] = $setup_name;
        $raw_json_encoce=json_encode($raw_json);
        echo $raw_json_encoce;
       
        $session_val= is_session_valid();

    if($session_val==0){
    }

    else{      
    }
}
$logfile->logfile_close();
?>