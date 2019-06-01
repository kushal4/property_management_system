<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside save_setup_prop.php";

if (is_ajax()) {
    
    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);

    $first_name="";
    $last_name="";
    $gender="";
    $birth_date="";
    $rel_with_owner="";
    $com_addr_flat_num="";
    $com_addr_prop_name="";
    $com_addr_street_1="";
    $com_addr_street_2="";
    $com_addr_locality="";

    $com_addr_city="";
    $com_addr_state="";
    $com_addr_country="";
    $com_addr_postal_code="";
    $com_addr_phone1="";
    $com_addr_phone1_type="";
    $com_addr_phone2="";
    $com_addr_phone2_type="";
    $com_addr_phone3="";
    $com_addr_phone3_type="";

    $com_addr_phone4="";
    $com_addr_phone4_type="";
    $blood_group="";
    $emer_phone1="";
    $emer_phone1_type="";
    $emer_phone1_name="";
    $emer_phone1_relation="";
    $emer_phone2="";
    $emer_phone2_type="";
    $emer_phone2_name="";

    $emer_phone2_relation="";
    
        foreach ($json_decoded as $key => $value) {
            //echo $key. "=>>>>>" .$value;
           // $log_str.="\n $key =>>>>> $value \n";
            if ($key=="first_name"){
                $first_name = $value;
                //echo "the name is".$setup_name;
            }
            if ($key=="last_name"){
                $last_name = $value;
                //echo "the add line1 is".$setup_add1;
            }
            if ($key=="gender"){
                $gender = $value;
               //echo "the add line2 is".$setup_add2;
            }
            if ($key=="dob"){
                $birth_date = $value;
               //echo "the locality is".$setup_locality;
            }
            if ($key=="rel_with_owner"){
                $rel_with_owner = $value;
               //echo "the city is".$setup_city;
            }
            if ($key=="com_addr_flat_num"){
                $com_addr_flat_num = $value;
                //echo "the state is".$setup_state;
            }
            if ($key=="com_addr_prop_name"){
                $com_addr_prop_name = $value;
               //echo "the country is".$setup_country;
            }
            if ($key=="com_addr_street_1"){
                $com_addr_street_1 = $value;
                //echo "the pincode is".$setup_pincode;
            }
            if ($key=="com_addr_street_2"){
                $com_addr_street_2 = $value;
               //echo "the country is".$setup_country;
            }
            if ($key=="com_addr_locality"){
                $com_addr_locality = $value;
                //echo "the pincode is".$setup_pincode;
            }




            if ($key=="com_addr_city"){
                $com_addr_city = $value;
                //echo "the name is".$setup_name;
            }
            if ($key=="com_addr_state"){
                $com_addr_state = $value;
                //echo "the add line1 is".$setup_add1;
            }
            if ($key=="com_addr_country"){
                $com_addr_country = $value;
               //echo "the add line2 is".$setup_add2;
            }
            if ($key=="com_addr_postal_code"){
                $com_addr_postal_code = $value;
               //echo "the locality is".$setup_locality;
            }
            if ($key=="com_addr_phone1"){
                $com_addr_phone1 = $value;
               //echo "the city is".$setup_city;
            }
            if ($key=="com_addr_phone1_type"){
                $com_addr_phone1_type = $value;
                //echo "the state is".$setup_state;
            }
            if ($key=="com_addr_phone2"){
                $com_addr_phone2 = $value;
               //echo "the country is".$setup_country;
            }
            if ($key=="com_addr_phone2_type"){
                $com_addr_phone2_type = $value;
                //echo "the pincode is".$setup_pincode;
            }
            if ($key=="com_addr_phone3"){
                $com_addr_phone3 = $value;
               //echo "the country is".$setup_country;
            }
            if ($key=="com_addr_phone3_type"){
                $com_addr_phone3_type = $value;
                //echo "the pincode is".$setup_pincode;
            }




            if ($key=="com_addr_phone4"){
                $com_addr_phone4 = $value;
                //echo "the name is".$setup_name;
            }
            if ($key=="com_addr_phone4_type"){
                $com_addr_phone4_type = $value;
                //echo "the add line1 is".$setup_add1;
            }
            if ($key=="blood_group"){
                $blood_group = $value;
               //echo "the add line2 is".$setup_add2;
            }
            if ($key=="emer_phone1"){
                $emer_phone1 = $value;
               //echo "the locality is".$setup_locality;
            }
            if ($key=="emer_phone1_type"){
                $emer_phone1_type = $value;
               //echo "the city is".$setup_city;
            }
            if ($key=="emer_phone1_name"){
                $emer_phone1_name = $value;
                //echo "the state is".$setup_state;
            }
            if ($key=="emer_phone1_relation"){
                $emer_phone1_relation = $value;
               //echo "the country is".$setup_country;
            }
            if ($key=="emer_phone2"){
                $emer_phone2 = $value;
                //echo "the pincode is".$setup_pincode;
            }
            if ($key=="emer_phone2_type"){
                $emer_phone2_type = $value;
               //echo "the country is".$setup_country;
            }
            if ($key=="emer_phone2_name"){
                $emer_phone2_name = $value;
                //echo "the pincode is".$setup_pincode;
            }


            if ($key=="emer_phone2_relation"){
                $emer_phone2_relation = $value;
               //echo "the country is".$setup_country;
            }
            
        } 

        $conn = new \mysqli($server_name, $user_name, $password, $dbname);
        $user_id = $SENSESSION->get_val("user_id");

          // Get the input from form
        $mod_code = "new";
        
        $sql_insrt = "INSERT INTO user_profile(user_id, first_name, last_name, gender, birth_date, rel_with_owner, 
            com_addr_flat_num, com_addr_prop_name, com_addr_street_1, com_addr_street_2, com_addr_locality,
            com_addr_city, com_addr_state, com_addr_country, com_addr_postal_code,
            com_addr_phone1, com_addr_phone1_type, 	com_addr_phone2, com_addr_phone2_type,
            com_addr_phone3, com_addr_phone3_type, com_addr_phone4, com_addr_phone4_type,
            blood_group, emer_phone1, emer_phone1_type, emer_phone1_name, emer_phone1_relation,
            emer_phone2, emer_phone2_type, emer_phone2_name, emer_phone2_relation, mod_code, mod_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        
        $sql_temp = $conn->prepare($sql_insrt);
        if($sql_temp){
            echo "prepare success";
            $sql_bind = $sql_temp->bind_param("issssssssssssssisisisississsissssi", $user_id, $first_name, $last_name, $gender, $birth_date, $rel_with_owner, 
            $com_addr_flat_num, $com_addr_prop_name, $com_addr_street_1, $com_addr_street_2, 
            $com_addr_locality, $com_addr_city, $com_addr_state, $com_addr_country, $com_addr_postal_code, 
            $com_addr_phone1, $com_addr_phone1_type, $com_addr_phone2, $com_addr_phone2_type, 
            $com_addr_phone3, $com_addr_phone3_type, $com_addr_phone4, $com_addr_phone4_type, $blood_group, 
            $emer_phone1, $emer_phone1_type, $emer_phone1_name, $emer_phone1_relation, 
            $emer_phone2, $emer_phone2_type, $emer_phone2_name, $emer_phone2_relation, $mod_code, $user_id);
            if($sql_bind){
                echo "bind success";
                $sql_exe = $sql_temp->execute();
                echo "exec error= ".$sql_temp->error."</br>";
                if($sql_exe){
                    echo "execution success";
                }else{
                    echo "execution failed";
                }
            }else{
                echo "bind failure";
            }

        }else{
            echo "prepare failed";
        }

        
        
        
        

        $raw_json["ret_code"] = 0;
        $raw_json["ret_msg"] = "details saved successfully";
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