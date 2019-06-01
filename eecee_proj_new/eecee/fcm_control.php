<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
$fcm_post_data_jsn_obj = $_POST["param"];
//$fcm_post_data_jsn_obj = json_decode($raw_json_str, true);
$message=$fcm_post_data_jsn_obj["message"];
$token_id=$fcm_post_data_jsn_obj["token"];
$device_id=$fcm_post_data_jsn_obj["device_id"];
sendFCM($message,$token_id,$device_id);
}

function sendFCM($mess,$id,$device_id) {
$url = 'https://fcm.googleapis.com/fcm/send';
$fields = array (
        'to' => $id,
        'notification' => array (
                "body" => $mess,
                "title" => "Title",
                "icon" => "myicon"
        ),
      'data'=>array("device_id"=> "$device_id","body" => $mess)
);
$fields = json_encode ( $fields );
$headers = array (
        'Authorization: key=' . "AIzaSyAONiA1Tr6Vg2ZAyJ6UXEHEX5fMLz4JQww",
        'Content-Type: application/json'
);

$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_POST, true );
curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

$result = curl_exec ( $ch );

echo "result ".$result;

curl_close ( $ch );
}

?>
