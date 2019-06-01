<?php 

function CurlSendPostJson($url,$datajson,$logfile=NULL, $funcname=NULL){
    //echo "getting inside this**************";
    $logfile->logfile_writeline("getting inside CurlSendPostJson");
    $logfile->logfile_writeline("the URL is :: ".$url);
    //$logfile->logfile_writeline("the datajson is :: ".$datajson);
    //$logfile->logfile_writeline("getting inside this"); 
    $fname = ($funcname==NULL) ? "NOFUNC" : $funcname;
    if ($logfile !=NULL) {
        $logfile->logfile_writeline($fname."::cURL: START ".$url); 
        $logfile->logfile_writeline($fname."::To cURL: ".$datajson); 
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datajson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($datajson)));
    //curl_setopt($ch,CURLOPT_HEADER, true); //if you want headers
    $result = curl_exec($ch);
    if ($logfile !=NULL) {
        $logfile->logfile_writeline($fname."::From cURL: ".$result); 
        $logfile->logfile_writeline($fname."::cURL: END ".$url); 
    }

    return $result;
}

?>
