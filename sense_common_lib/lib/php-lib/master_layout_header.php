
<?php

$user_id = $_SESSION["user_id"];
$username = $_SESSION["login_user"];
$firstname = $_SESSION["first_name"];
$lastname = $_SESSION["last_name"];
$user_full_name = $_SESSION["user_name"];

$logged_role = $_SESSION["logged_role_name"];

//echo $user_id;
//echo "the first name is :: ".$firstname;
//echo "the first name is :: ".$firstname;

$admin = $_SESSION["admin"];

$proxy_user_id = $_SESSION["user_id"];
$proxy_username = $_SESSION["proxy_user"];
$proxy_firstname = $_SESSION["proxy_first_name"];
$proxy_lastname = $_SESSION["proxy_last_name"];


$html.="        <div id=\"header-cont\">";
$html.="            <div id=\"header-cont-left\">";
$html.="            </div>";
$html.="            <div id=\"header-cont-center\">";
$html.="                <div id=\"logo\">";

$html.="                </div>";
$html.="                <div class=\"headertab\" style=\"float: left\">";
$html.="                    <button class=\"tablinks\" id=\"my_acc\" onclick=\"\">My Account</button>";
$html.="                    <button class=\"tablinks\" id=\"dev\" onclick=\"\">Dashboard</button>";
$html.="                    <button class=\"tablinks\" id=\"accsrs\" onclick=\"\">Accessories</button>";
$html.="                    <button class=\"tablinks\" id=\"tstmnls\" onclick=\"\">Testimonials</button>";
$html.="                    <button class=\"tablinks\" id=\"contact_us\" onclick=\"\">Contact Us</button>";                           
$html.="                </div>";

$html.="                <div id=\"user_image\">";
/*
if($user_id == 3){
    //echo "insert admin textbox";
    //$html.="<input type='text' class='admin_set_input' name='fname'>";
    //$html.="<button type='button' class='admin_set_butt'>SET</button>";
    //echo $_SERVER['SCRIPT_NAME'];
    //printAllServerVariables();
    if (isSelfPost()){
        if(isset($_POST['newacc_id'])) {
            $_SESSION["acc_id"] = $_POST['newacc_id'];
        }
    }
    $postback = basename($_SERVER['PHP_SELF']);

    $html.= "<form name='myform' action='$postback' method='post'>";
    $html.= "<input type ='text' class='admin_set_input' name='newacc_id'><br>";
    $html.= "<button type ='submit' name='submit' class='admin_set_butt'>SET</button>";
    $html.= "</form>";

}*/
$html.="                <img onclick=\"myFunction()\" src=\"../themes/images/userlogo.png\" alt=\"userimage\" class=\"dropbtn\" />";
$html.="                <div>";
$html.="                    <div id=\"myDropdown\" class=\"dropdown-content\">";
$html.="                        <div class=\"triangle-up\">";
$html.="                        </div>";
$html.="                        <div class=\"dropdown_container\">";
$html.="                            <div class=\"logged_in_usr_div_styl\">";
if($admin == 1){
    $html.="                            <span id=\"login_user_name_heading\"  class=\"login_user_name_heading_styl\">Logged-In User</span>";    
}
$html.="                                <span id=\"login_user_name\"  class=\"login_user_name_style\">$user_full_name </span>";
$html.="                                 <span id=\"login_user_email\">($username)</span>";
$html.="                                 <div id=\"logged_in_role_cont\" class=\"logged_in_role_style\">";
$html.="                                 <span id=\"logged_in_role_key\" class=\"logged_in_role_key_style\">Role: </span>";
$html.="                                 <span id=\"logged_in_role_value\" class=\"logged_in_role_value_style\">$logged_role</span>";
$html.="                                 </div>";
$html.="                            </div>";

if($admin == 1){
    $html.="                        <div class=\"proxy_usr_div_styl\">";
    $html.="                            <span id=\"proxy_login_user_name_heading\"  class=\"login_user_name_heading_styl\">Proxy User</span>";    
    $html.="                            <span id=\"proxy_login_user_name\"  class=\"proxy_login_user_name_styl\">$proxy_firstname $proxy_lastname</span>";
    $html.="                            <span id=\"proxy_login_user_email\">$proxy_username</span>";
    $html.="                            <div id=\"set_a\" class=\"set_a_styl\">";
    
    $html.="                                    <a id=\"set_acc_id\" class=\"set_acc_id_styl\">Set Proxy</a>";
    $html.="                            </div>";
    $html.="                        </div>";
}
/*
if($admin == 1){
    $html.="                            </div>";
    $html.="                            <div id=\"set_a\" class=\"set_a_styl\">";
    
    $html.="                                    <a id=\"set_acc_id\" class=\"set_acc_id_styl\">Set Proxy</a>";
    $html.="                            </div>";
}
*/
$html.="                            <div id=\"logout\">";
$html.="                                <div class=\"logout_button\">";
$html.="                                    <a href=\"$logout_path\" class=\"logout_button_anchor\">Logout</a>";
$html.="                                </div>";



$html.="                        </div>";
$html.="                    </div>";
$html.="                </div>";
$html.="            </div>";
$html.="            </div>";
$html.="            <div id=\"header-cont-right\">";
$html.="            </div>";
$html.="        </div>";
?>
