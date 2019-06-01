<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include '../eecee_include.php';
include $sense_common_php_lib_path . 'sec.php';
function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
class ret
{
    public $status;
    public $message;
}
if (is_ajax()) {

    $request_method = $_POST["method"];
    $out = new ret();
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);

    if ($conn->connect_error) {
        $out->status = 1;
        $out->message = "Connection failure";
        return json_encode($out);
        // die("Connection failed: " . $conn->connect_error);
    }
    $prop_id = $_SESSION["prop_id"];

    try {
        switch ($request_method) {
            case 'create':
                 $max=1;
                 $cat_name = $_POST["cat_name"];
                $get_max_order_sql="SELECT MAX(order_hesk)'max' from `hesk_priorities` WHERE prop_id=?";
                $get_max_cmd=$conn->prepare($get_max_order_sql);
                $get_max_cmd->bind_param("i", $prop_id);
                $get_max_cmd->execute();
                $result = $get_max_cmd->get_result();
                $row=$result->fetch_assoc();
                $rowcount=mysqli_num_rows($result);
                if( $rowcount>0){
                    $max+=$row["max"];
                }
             






                $hesk_sql = "INSERT INTO `hesk_priorities`(`prop_id`, `name`, `order_hesk`) VALUES (?,?,?)";
                $hesk_cmd = $conn->prepare($hesk_sql);
                $hesk_cmd->bind_param("isi", $prop_id, $cat_name, $max);
                $hesk_cmd->execute();
                $out->status = 0;

                break;
            case 'update':
                $sec_id = $_POST["s"];
                $name = $_POST["text"];
                $id = sec_get_map_val("issue_prio_map", $sec_id);
                $hesk_sql = "UPDATE `hesk_priorities` SET name=? WHERE id=?";
                $hesk_cmd = $conn->prepare($hesk_sql);
                $hesk_cmd->bind_param("si", $name, $id);
                $hesk_cmd->execute();

                $out->status = 0;
                break;
            case 'delete':
                //$out->status = 0;
                $sec_id = $_POST["s"];
                $id = sec_get_map_val("issue_prio_map", $sec_id);
                $hesk_sql = "DELETE FROM `hesk_priorities` WHERE id=?";
                $hesk_cmd = $conn->prepare($hesk_sql);
                $hesk_cmd->bind_param("i", $id);
                $hesk_cmd->execute();
                $out->status = 0;
                break;
            case 'change_pos':
               $source_sec=$_POST["source"];
               $destination_sec=$_POST["destination"];

               if($source_sec==null || $destination_sec==null){

               }
               else{
                $source_sec_id=sec_get_map_val("issue_prio_map", $source_sec);
                $destination_sec_id=sec_get_map_val("issue_prio_map", $destination_sec);

                $get_source_sql="SELECT * from `hesk_priorities` WHERE id=?";
                $get_source_cmd=$conn->prepare($get_source_sql);
                $get_source_cmd->bind_param("i", $source_sec_id);
                $get_source_cmd->execute();
                $srcresult = $get_source_cmd->get_result();
                $sourcerow=$srcresult->fetch_assoc();

                $get_dest_sql="SELECT * from `hesk_priorities` WHERE id=?";
                $get_dest_cmd=$conn->prepare($get_dest_sql);
                $get_dest_cmd->bind_param("i", $destination_sec_id);
                $get_dest_cmd->execute();
                $destresult = $get_dest_cmd->get_result();
                $destrow=$destresult->fetch_assoc();

                $order_hesk_src=$sourcerow["order_hesk"];
                $order_hesk_dest=$destrow["order_hesk"];

                $hesk_sql = "UPDATE `hesk_priorities` SET order_hesk=? WHERE id=?";
                $hesk_cmd = $conn->prepare($hesk_sql);
                $hesk_cmd->bind_param("ii", $order_hesk_dest, $source_sec_id);
                $hesk_cmd->execute();

                $hesk_cmd->bind_param("ii", $order_hesk_src, $destination_sec_id);
                $hesk_cmd->execute();
               }

                
                  


                break;
            default:
                $out->$status = 2;
                $out->message = "Undefined request";
        }

    } catch (Exception $e) {
        $out->$status = 3;
        $out->$message = $e->getMessage();

    }

    return json_encode($out);

}
