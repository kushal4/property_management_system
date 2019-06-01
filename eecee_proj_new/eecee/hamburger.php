
<!DOCTYPE html>
<html>
<head>
<script src="ext_lib/js-lib/jquery-3.2.1.js"></script>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<?php

?>

<body>
  <div id="body_cont">
   <?php
//require_once "orig_cont.php";
require_once "button_view.php"
?>
  </div>
<?php
header("Access-Control-Allow-Origin: *");

?>


<script>

function myMap() {
var mapProp= {
  center:new google.maps.LatLng(51.508742,-0.120850),
  zoom:5,
};
console.log("call google map javascript");
var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
}

      $(document).ready(function(){


          var bodycontent=$("#body_cont");
           $(document).on("click", "button#map_but" ,function(){
             //console.log(bodycontent);
             //bodycontent.load("http://192.168.0.25/opt/eecee_proj_new/eecee/Details.php #detail_cont",{}, function (response, status, xhr) {
              bodycontent.load("http://192.168.0.25/opt/eecee_proj_new/eecee/map_view.php",{}, function (response, status, xhr) {
                function initMap() {
        map = new google.maps.Map(document.getElementById('googleMap'), {
          center:new google.maps.LatLng(51.508742,-0.120850),
          zoom:5
        });
      }
                $.getScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyBHQXMX6wKssma71Ps8Zy0sgSAiOVp5uoU",function (response, status, xhr) {
                 
                  var mapProp= {
  center:new google.maps.LatLng(51.508742,-0.120850),
  zoom:5,
};
var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
          
                });

             });
          });
          $(document).on("click", "button#go_back_but" , function() {
          //$(".paypal_but").click(function(){
           // console.log("button back");
            bodycontent.load("http://192.168.0.25/opt/eecee_proj_new/eecee/button_view.php #main_cont",{}, function (response1, status, xhr) {
               console.log(response1);
            });
          });
      })
    </script>
   
</body>
</html>
<!--/.Navbar-->