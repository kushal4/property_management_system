
<?php
//echo "getting inside master_layout.php";

//$doc = new DOMDocument();
//echo "after new DOMDocument()";
$html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
$html.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
$html.="<html xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:v=\"urn:schemas-microsoft-com:vml\">";
$html.="<head>";
$html.="<title>EECEE</title>";
$html.="<meta http-equiv=\"Content-Type\" content=\"application/xhtml+xml; charset=utf-8\" />";
$html.="<meta name=\"robots\" content=\"index, follow\" />";
$html.="<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />";
$html.="<link rel=\"stylesheet\" type=\"text/css\" href=\"../../../eecee_proj_new/eecee/themes/holygrail.css\" />";
$html.="<!--[if lt IE 7]>";
$html.=" <style media=\"screen\" type=\"text/css\">";
$html.=" .col1 {";
$html.="     width:100%;";
$html.="    }";
$html.=" </style>";
$html.=" <![endif]-->";
$html.="</head>";
$html.="<body onload=\"javascript:body_load_func();\">";
$html.="    <div id=\"header\">";

include "master_layout_header.php";

$html.="    </div>";
$html.="    <div class=\"colmask holygrail\">";
$html.="        <div class=\"colmid\">";
$html.="            <div class=\"colleft\">";
$html.="                <div class=\"col1wrap\">";
$html.="                    <div class=\"col1\" id=\"main_container\">";
$html.="                        <!-- Column 1 start -->";
$html.="                    </div>";
$html.="                </div>";
$html.="                <div class=\"col2\">";
$html.="                    <!-- Column 2 start -->";
$html.="                    <!-- Column 2 end -->";
$html.="                </div>";
$html.="                <div class=\"col3\">";
$html.="                    <!-- Column 3 start -->";
$html.="                    <!-- Column 3 end -->";
$html.="                </div>";
$html.="            </div>";
$html.="        </div>";
$html.="    </div>";
$html.="    <div id=\"footer\">";
include "master_layout_footer.php";
$html.="    </div>";
$html.="</body>";
$html.="</html>";

$doc = createDomDoc($html);
//$doc->loadHTML($html);

$doc_head = $doc->getElementsByTagName('head')->item(0);
$doc_body = $doc->getElementsByTagName('body')->item(0);
$layout_main_container =$doc->getElementById('main_container');
$layout_header_container =$doc->getElementById('header');
$layout_footer_container =$doc->getElementById('footer');
?>





