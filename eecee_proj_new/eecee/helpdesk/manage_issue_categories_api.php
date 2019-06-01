
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
include '../prop_topo.php';

require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_sec_map.php';
include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';


function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
class ret
{
    public $status=0;
    public $message="";
    public $nodes=array();
}
//echo "getting inside get_prop_topo.php";

if (is_ajax()) {
    $out=new ret();
    $method=$_POST["method"];
    $prop_id = $_SESSION["prop_id"];
    try {
        $conn = new \mysqli($server_name, $user_name, $password, $dbname);

        if ($conn->connect_error) {
            $out->status = 1;
            $out->message = "Connection failure";
            //return json_encode($out);
            // die("Connection failed: " . $conn->connect_error);
        }
        else{
            switch($method){
                case "list":
                $select_sql="SELECT * FROM `hesk_categories` where prop_id=? AND deleted=0";
                $select_cmd=$conn->prepare($select_sql);
                $select_cmd->bind_param("i", $prop_id);
                $select_cmd->execute();
                $result = $select_cmd->get_result();
              
               $out->status=0;
               $out->message="";
                while($row = $result->fetch_assoc()) {
                   // $temp_arr=array();
                    $row_id=$row["id"];
                    $temp=array("id"=>$row["id"],"name"=>$row["name"],"parent"=>$row["parent"]);
                    //$temp_arr[$row_id]=$temp;
                    array_push($out->nodes,$temp);
                   
        
                }
                break;
                case "create":
                  $text=$_POST["text"];
                  $parent=$_POST["parent"];
                  $parent_id="0";
                  if($parent!="#"){
                     // $parent_id= $parent;
                      $str_arr = explode ("_", $parent);
                      //$parent_id= 5;
                      $parent_id=$str_arr[1];
                  }
                  $select_sql="INSERT INTO `hesk_categories`(`name`, `parent`, `prop_id`) VALUES (?,?,?)";
                  $select_cmd=$conn->prepare($select_sql);
                  $select_cmd->bind_param("sii", $text,$parent_id, $prop_id);
                  $select_cmd->execute();
                  $out->status=0;
                  $out->message="parent_id".$parent_id;

                break;
                case "update":
                $sec_id=$_POST["s"];
                $str_arr = explode ("_", $sec_id);
                $text=$_POST["text"];
                $update_sql="UPDATE `hesk_categories` SET `name`=? WHERE `id`=?";

                $update_cmd=$conn->prepare($update_sql);
                $update_cmd->bind_param("si", $text,$str_arr[1]);
                $update_cmd->execute();
                $out->status=0;
                
                 
                break;
                case "delete":
                
                $sec_id=$_POST["s"];
                $str_arr = explode ("_", $sec_id); 
                $update_sql="UPDATE `hesk_categories` SET `deleted`=1 WHERE `id`=?";

                $update_cmd=$conn->prepare($update_sql);
                $update_cmd->bind_param("i", $str_arr[1]);
                $update_cmd->execute();
                $out->status=0;
                $out->message="";
                break;

                case "paste":
                $what=$_POST["what"];
                $src=$_POST["src"];
                $src_arr = explode ("_", $src);


                $dest=$_POST["dest"];
                $dest_arr=explode ("_", $dest);
                if($what=="cut"){
                    $update_sql="UPDATE `hesk_categories` SET `parent`=? WHERE `id`=?";

                $update_cmd=$conn->prepare($update_sql);
                $update_cmd->bind_param("ii",  $dest_arr[1], $src_arr[1]);
                $update_cmd->execute();
                $out->status=0;

                }
                else if($what=="copy"){
                    $select_sql="SELECT * FROM `hesk_categories` WHERE id=?";
                    $select_cmd=$conn->prepare($select_sql);
                    $select_cmd->bind_param("i", $src_arr[1]);
                    $select_cmd->execute();
                    $result = $select_cmd->get_result();
                    $row = $result->fetch_assoc();
                    $text=$row["name"];

                    $select_sql="INSERT INTO `hesk_categories`(`name`, `parent`, `prop_id`) VALUES (?,?,?)";
                    $select_cmd=$conn->prepare($select_sql);
                    $select_cmd->bind_param("sii", $text, $dest_arr[1], $prop_id);
                    $select_cmd->execute();
                    

                    $out->status=0;
                       
                }
                $out->message= $what. $src.$dest;
                break;
                default:
                break;
            }
           

        }
      




     
    } catch (Exception $e) {
    $out->status = 3;
    $out->message = $e->getMessage();

    }
}
    echo json_encode($out);
   
?>
