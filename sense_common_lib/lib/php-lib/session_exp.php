<?php

//echo $log_path;

//$logfile = new \Sense\Log("/home/steffi/Steffi_usr/stef/eecee/$log_path", __FILE__);
//$logfile->logfile_open("a");
//include '../tarantoo/lib/php-lib/curl_url_include.php';
//$logfile->logfile_writeline(": value of login user::".$_SESSION["login_user"]);


function is_session_valid(){
    
    if(isset($_SESSION["login_user"]))
    {
        return 0;
    }
    else{
        
        return $GLOBALS["session_exp_val"];
    }
    //return 0;
}
?>
