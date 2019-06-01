<?php

function gen_rand_key($length){
    // $string = '';
    $rem_lttrs="";
    $characters="abcdefghijklmnopqrstqvwxyzABCDEFGHIJKLMNOPQRSTQVWXYZ0123456789";
    $max = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++){
    $rem_lttrs .= $characters[mt_rand(0, $max)];
    }
    return $rem_lttrs;
    }

function sec_push_map ($map_name, $map){
    $_SESSION[$map_name]=$map;
}

function sec_push_map_single_entry ($map_name, $key, $val){
    $curr_map=$_SESSION[$map_name];
    $curr_map[$key] = $val;
    $_SESSION[$map_name]=$curr_map;
}

function sec_push_val_single_entry ($map_name, $val){
    $curr_map=$_SESSION[$map_name];
    $key = gen_unique_sec_id($curr_map);
    $curr_map[$key] = $val;
    $_SESSION[$map_name]=$curr_map;
    return $key;
}

function sec_clear_map ($map_name){
    $_SESSION[$map_name] = array();
}

function sec_get_map_val ($map_name, $key){
    $curr_map=$_SESSION[$map_name];
    return $curr_map[$key];
}

function sec_get_map ($map_name){
    $curr_map=$_SESSION[$map_name];
    return $curr_map;
}

//Function sec_gen_map
//
//Takes an array of object, which has primary key from underlying database
//and modifies the array by obfuscating the primary key with a 16 character random string.
//Returns a mapping array that contains association between the random string and
//the original primary key
//
//Arguments
//=========
//$entity_array: An array of objects. ***CAUTION: IT IS PASSED BY REFERENCE***
//Each object contains a key whose value is primary key.
//The primary key identifies the entry of the array
//e.g. entry format => {"net_id":<primary key>, "some_key":<some_value>,....}
//Here "net_id" is the specific key whose value is primary key of the entry
//
//$id_name:
//Name of the object key whose value is primary key
//e.g. "net_id":38, here id_name is 'net_id'
//
//Returns: a security map whose key is a random 16 character ID, and value is the
//primary key of the entity array
//
//CAUTIONARY NOTE:
//The original entity array is modified by this function as following,
//Value, which contained primary key of the entry,
//of its $id_name field is replaced by a 16 character random string.

function sec_gen_map_orig(&$entity_array, $id_name){
    $sec_map = array();
    //Add a rand_id key for each entry of the entity array
    for ($x = 0; $x < count($entity_array); $x++) {
        $rand_id = gen_rand_key(16);
        //TO DO: Check if this rand_id is already existing in entity array
        $entity_array[$x]["rand_id"] = $rand_id;
    }
    //populate the {rand_id=>primary id} mapping array
    foreach ($entity_array as $entity) {
        //$logfile->logfile_writeline($net["net_id"]." : ".$net["network_name"]." : ". $net["key"]);
        $sec_map[$entity["rand_id"]]=$entity[$id_name];
    }
    //overwriting primary id key with rand_id key for each entry of the entity array
    for ($x = 0; $x < count($entity_array); $x++) {
        $entity_array[$x][$id_name] = $entity_array[$x]["rand_id"];
    }
     //removing "rand_id" key so that
     //entity array (which is returned to browser)
     //does not see this key name
    for ($x = 0; $x < count($entity_array); $x++) {
        unset ($entity_array[$x]["rand_id"]);
    }
    return $sec_map;
}

function gen_unique_sec_id($sm){
    //echo "the primary ID is:: ".$sm;
    $rand_id = gen_rand_key(16);
   // echo "the random ID is:: ".$rand_id;
    /*
    while (array_key_exists($rand_id, $sm)) { //rand_id exists already
        $rand_id = gen_rand_key(16); //generate a new rand_id
    }*/
    return $rand_id;
}


function sec_gen_map(&$entity_array, $id_name){
    $sec_map = array();
    foreach ($entity_array as &$entity) { //Note: $entity is used by reference so that we can directly modify the entry
        gen_unique_sec_id($sec_map);
        /*
        $rand_id = gen_rand_key(16);
        //rand_id must be unique inside sec_map
        //Check if this rand_id is existing in sec_map
        //If so, generate a new rand_id
        //Keep chekcking and generating new rand_id till we find
        //an unique rand_id
        while (array_key_exists($rand_id, $sec_map)) { //rand_id exists already
            $rand_id = gen_rand_key(16); //generate a new rand_id
        }
        */
        //we come out of the while loop when we found an unique rand_id
        $sec_map[$rand_id]=$entity[$id_name]; //creating the entry in the sec_map array
        $entity[$id_name] = $rand_id; //overwriting entity array entry with this rand_id
    }
    return $sec_map;
}

function dump_sec_map($map_name, $marker=""){
    $print_str = "";
    $curr_map = sec_get_map($map_name);
    $print_str .= __FILE__."---Dumping ".$map_name." MAP: Begin".$marker;
    foreach($curr_map as $key => $value)
    {
        $print_str .= $key." : ".$value.$marker;
    }

    $print_str .= __FILE__."---Dumping ".$map_name." MAP: End".$marker;


    /*
$logfile->logfile_writeline(__FILE__."---Dumping fld_sig_map MAP: Begin");
        foreach($curr_map as $key => $value)
            {
                $logfile->logfile_writeline($key." : ".$value);
            }
$logfile->logfile_writeline(__FILE__."---Dumping fld_sig_map MAP: End");
*/

    return $print_str;

}


?>