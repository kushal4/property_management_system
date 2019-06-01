<?php
   
function isSelfPost(){
    $ret=false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $referer = "";
        $thisPage = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        
        if (isset($_SERVER['HTTP_REFERER'])){
            $referer = $_SERVER['HTTP_REFERER'];
            $ref_url = $referer;
            $ref_url = parse_url($referer, PHP_URL_SCHEME)."://".parse_url($referer, PHP_URL_HOST).parse_url($referer, PHP_URL_PATH);
            //$ref_url = $ref_parsed["PHP_URL_PATH"];
            //$ref_url = $ref_parsed.PHP_URL_PATH;
        }
        //echo "ref=".$ref_url."<br>";
        //echo "thisPage=".$thisPage."<br>";
        if ($ref_url == $thisPage){
            $ret=true;
        } 
    }
    return $ret;
}

function printAllServerVariables(){
    while (list($var,$value) = each ($_SERVER)) {
        echo "$var => $value <br />";
    }

}

?>