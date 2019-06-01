<?php



$sense_common_php_lib_path = '../../../sense_common_lib/lib/php-lib/';
$sense_common_js_lib_path = '../../../sense_common_lib/lib/js-lib/';

$eecee_ext_php_lib_path = '../ext_lib/php-lib/';
$eecee_ext_js_lib_path = '../ext_lib/js-lib/';
$eecee_ext_styles_path = '../ext-styles/';

$eecee_php_lib_path = '../lib/php-lib/';
$eecee_js_lib_path = '../lib/js-lib/';
$eecee_styles_path = '../themes/';
$eecee_log_path = '../Logs/';

//Feature paths
$eecee_reg_flow_path = '../reg/';
$eecee_reg_flow_path_comm = 'eecee_proj_new/eecee/reg/';

$eecee_login_flow_path = '../login/';
$eecee_access_path = '../acc/';
$eecee_prop_def_path = '../propdef';
$eecee_helpdesk_path = '../helpdesk/';
$eecee_swfm_path = '../swfm/';
require_once $sense_common_php_lib_path.'sensession.php';

$SENSESSION=sensession::getIstance();

if ($SENSESSION->token_exists("baseurl") && ($SENSESSION->get_val("baseurl") != NULL) ) {
    $baseurl = $SENSESSION->get_val("baseurl");
    //echo "baseurl exists in session<br>";
    //var_dump($baseurl);
} else {
    if (isset($_GET["en"])) {
        $enc = $_GET["en"];
        //echo "enc=$enc<br>";
        if ( ($enc==null) || ($enc=="") || ($enc==0) || ($enc=="0") ) {
            //echo "here 1 <br>";
            $baseurl = "actl";    
        } else {
            //echo "here 2<br>";
            $baseurl = "enactl";    
        }
    } else {
        //echo "here 3<br>";
        $baseurl = "actl";
    }

    $SENSESSION->token("baseurl", $baseurl);
}


include 'eecee_constants.php';



//echo "from include::".$_SESSION["baseurl"];
$appname = "EECEE";
$eecee_server_ip = "http://192.168.0.12";

$eecee_client_ip = "http://192.168.0.25";

$actl_ip = "http://192.168.0.12";
//192.168.1.102


$smtp_server_url = "smtp.gmail.com";
$smtp_server_port = 587;
$new_reg_step1_email_sender = "sensennium.kushal4@gmail.com"; //Sending Verification email
$new_reg_step1_email_sender_pass = "05051993pom";

$new_reg_step2_email_sender = "sensennium.kushal4@gmail.com"; //Confirming Registration + temp password
$forget_pass_step1_email_sender = "sensennium.kushal4@gmail.com"; //Sending temp password after resetting old password

$eecee_client_hostname = $eecee_client_ip;

//$actlUpdateRoleCurlURL = $actl_ip."/$baseurl/actlApi.aspx/update_role";


//actlApi.aspx/create_client


    
//$key = str_shuffle($char_set);     







?>


