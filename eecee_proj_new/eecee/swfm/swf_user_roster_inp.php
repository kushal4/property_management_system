<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
function roster_view_create($parent_table){
    //$parent_table->setParent($feature_cat_table_container);
        $f_name_row_obj = $parent_table->addRow();
        $parent_table->setRowID($f_name_row_obj, "swf_fname");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $f_name_cell_obj = $parent_table->addCell("First Name", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("fname_swm_td");
        $fname_textbox='<input type="text" id="fname_text">';
        $f_name_inp_cell_obj=$parent_table->addCell($fname_textbox, FALSE);

        $l_name_row_obj = $parent_table->addRow();
        $parent_table->setRowID($l_name_row_obj, "swf_lname");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $l_name_cell_obj = $parent_table->addCell("Last Name", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("lname_swm_td");
        $lname_textbox='<input type="text" id="lname_text">';
        $l_name_inp_cell_obj=$parent_table->addCell($fname_textbox, FALSE);

        $email_name_1_row_obj = $parent_table->addRow();
        $parent_table->setRowID($email_name_1_row_obj, "swf_email_name_1");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $email_name_1_cell_obj = $parent_table->addCell("email name 1", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("email_name_1_swm_td");
        $email_name_1_textbox='<input type="text" id="email_name_1_text">';
        $email_name_1_inp_cell_obj=$parent_table->addCell($email_name_1_textbox, FALSE);

        $email_name_2_row_obj = $parent_table->addRow();
        $parent_table->setRowID($email_name_2_row_obj, "swf_email_name_2");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $email_name_2_cell_obj = $parent_table->addCell("email name 2", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("email_name_2_swm_td");
        $email_name_2_textbox='<input type="text" id="email_name_2_text">';
        $email_name_2_inp_cell_obj=$parent_table->addCell($email_name_2_textbox, FALSE);
        
        $gender_row_obj=$parent_table->addRow();
        $parent_table->setRowID($gender_row_obj, "gender_row");
        $gender_cell_obj = $parent_table->addCell("Gender", FALSE);

        $parent_table->setCurrCellID("gender_2_swm_td");
        $gender_sel_option='<select id="gender_name_list"><option value="Male">Male</option>
        <option value="Female">Female</option>Female<option value="Not Specified">Not Specified</option>';
        $gender_sel_cell_obj=$parent_table->addCell($gender_sel_option, FALSE);

        $dob_row_obj=$parent_table->addRow();
        $parent_table->setRowID($dob_row_obj, "dob_row");
        $dob_cell_obj = $parent_table->addCell("DOB", FALSE);

        $parent_table->setCurrCellID("dob_2_swm_td");
        $dob_inp_pick=' <input type="date" id="dob_date" name="bday">';
        $dob_inp_pick_cell_obj=$parent_table->addCell($dob_inp_pick, FALSE);

        $adhar_card_row_obj = $parent_table->addRow();
        $parent_table->setRowID($adhar_card_row_obj, "adhar_card");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $adhar_card_cell_obj = $parent_table->addCell("Adhar Card Number", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("adhar_card_swm_td");
        $adhar_card_textbox='<input type="text" id="adhar_card_text">';
        $adhar_card_inp_cell_obj=$parent_table->addCell($adhar_card_textbox, FALSE);


        $ration_card_row_obj = $parent_table->addRow();
        $parent_table->setRowID($ration_card_row_obj, "ration_card");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $ration_card_cell_obj = $parent_table->addCell("Ration Card Number", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("ration_card_swm_td");
        $ration_card_textbox='<input type="text" id="ration_card_text">';
        $aration_card_inp_cell_obj=$parent_table->addCell($ration_card_textbox, FALSE);

        
        $voter_card_row_obj = $parent_table->addRow();
        $parent_table->setRowID($voter_card_row_obj, "voter_card");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $voter_card_cell_obj = $parent_table->addCell("Voter Card Number", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("voter_card_swm_td");
        $voter_card_textbox='<input type="text" id="voter_card_text">';
        $voter_card_inp_cell_obj=$parent_table->addCell($voter_card_textbox, FALSE);

        $agency_name_row_obj = $parent_table->addRow();
        $parent_table->setRowID($agency_name_row_obj, "voter_card");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $agency_name_cell_obj = $parent_table->addCell("Agency Name", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("voter_card_swm_td");
        $agency_name_textbox='<input type="text" id="agency_name_text">';
        $agency_name_inp_cell_obj=$parent_table->addCell($agency_name_textbox, FALSE);
        

        $agency_cntct_f_name_row_obj = $parent_table->addRow();
        $parent_table->setRowID($agency_cntct_f_name_row_obj, "agency_cntct_f_name");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $agency_cntct_f_name_cell_obj = $parent_table->addCell("Agency Contact First Name", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("agency_cntct_f_name_swm_td");
        $agency_cntct_f_name_textbox='<input type="text" id="agency_cntct_f_name_text">';
        $agency_cntct_f_name_inp_cell_obj=$parent_table->addCell($agency_cntct_f_name_textbox, FALSE);


        $agency_cntct_l_name_row_obj = $parent_table->addRow();
        $parent_table->setRowID($agency_cntct_l_name_row_obj, "agency_cntct_l_name");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $agency_cntct_l_name_cell_obj = $parent_table->addCell("Agency Contact Last Name", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("agency_cntct_l_name_swm_td");
        $agency_cntct_l_name_textbox='<input type="text" id="agency_cntct_l_name_text">';
        $agency_cntct_l_name_inp_cell_obj=$parent_table->addCell($agency_cntct_l_name_textbox, FALSE);

        $agency_contct_designation_row_obj = $parent_table->addRow();
        $parent_table->setRowID($agency_contct_designation_row_obj, "agency_contct_designation");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $agency_contct_designation_cell_obj = $parent_table->addCell("Agency Contact Designation", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("agency_contct_designation_swm_td");
        $agency_contct_designation_textbox='<input type="text" id="agency_contct_designation_text">';
        $agency_cntct_l_name_inp_cell_obj=$parent_table->addCell($agency_contct_designation_textbox, FALSE);

        $agency_phone_num_1_ctry_code_obj = $parent_table->addRow();
        $parent_table->setRowID($agency_phone_num_1_ctry_code_obj, "agency_phone_num_1_ctry_code");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $agency_phone_num_1_ctry_code_cell_obj = $parent_table->addCell("Agency Phone number 1 country code", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("agency_phone_num_1_ctry_code_swm_td");
        $agency_phone_num_1_ctry_code_textbox='<input type="text" id="agency_phone_num_1_ctry_code_text">';
        $agency_phone_num_1_ctry_code_inp_cell_obj=$parent_table->addCell($agency_phone_num_1_ctry_code_textbox, FALSE);


        $agency_phone_num_1_obj = $parent_table->addRow();
        $parent_table->setRowID($agency_phone_num_1_obj, "agency_phone_num_1");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $agency_phone_num_1_cell_obj = $parent_table->addCell("Agency Phone number 1", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("agency_phone_num_1_swm_td");
        $agency_phone_num_1_textbox='<input type="text" id="agency_phone_num_1_text">';
        $agency_phone_num_1_inp_cell_obj=$parent_table->addCell($agency_phone_num_1_textbox, FALSE);


        


        $agency_phone_num_2_ctry_code_obj = $parent_table->addRow();
        $parent_table->setRowID($agency_phone_num_2_ctry_code_obj, "agency_phone_num_2_ctry_code");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $agency_phone_num_2_ctry_code_cell_obj = $parent_table->addCell("Agency Phone number 2 country code", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("agency_phone_num_2_ctry_code_swm_td");
        $agency_phone_num_2_ctry_code_textbox='<input type="text" id="agency_phone_num_2_ctry_code_text">';
        $agency_phone_num_2_ctry_code_inp_cell_obj=$parent_table->addCell($agency_phone_num_2_ctry_code_textbox, FALSE);

        $agency_phone_num_2_obj = $parent_table->addRow();
        $parent_table->setRowID($agency_phone_num_2_obj, "agency_phone_num_2");
        //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
        
        $agency_phone_num_2_cell_obj = $parent_table->addCell("Agency Phone number 2", FALSE);
        //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
        $parent_table->setCurrCellID("agency_phone_num_2_swm_td");
        $agency_phone_num_2_textbox='<input type="text" id="agency_phone_num_2_text">';
        $agency_phone_num_2_inp_cell_obj=$parent_table->addCell($agency_phone_num_2_textbox, FALSE);

        addr_gen_prototype("perm","Permanent Address",$parent_table);
        addr_gen_prototype("comm","Communicating Address",$parent_table);
}



function addr_gen_prototype($prefix,$header_text,$parent_table){
    $permanent_addr_header_row_obj = $parent_table->addRow();
    addAttribToElement($permanent_addr_header_row_obj, '{"class":"header_underline"}');
    $parent_table->setRowID($permanent_addr_header_row_obj, $prefix."_addr_header");
    $permanent_header_elem='<h2 style="text-decoration: underline;">'.$header_text.'</h2>';
    $permanent_addr_header_cell_obj = $parent_table->addCell($permanent_header_elem, FALSE);


    $perm_addr_1_row_obj = $parent_table->addRow();
    $parent_table->setRowID($perm_addr_1_row_obj, $prefix."_addr_1");
    //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
    
    $perm_addr_1_cell_obj = $parent_table->addCell("Address 1", FALSE);
    //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
    $parent_table->setCurrCellID($prefix."_addr_1_swm_td");
    $perm_addr_1_textbox='<input type="text" id="'.$prefix.'_addr_1_text">';
    $perm_addr_1_inp_cell_obj=$parent_table->addCell($perm_addr_1_textbox, FALSE);

    $perm_addr_2_row_obj = $parent_table->addRow();
    $parent_table->setRowID($perm_addr_2_row_obj, $prefix."_addr_2");
    //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
    
    $perm_addr_2_cell_obj = $parent_table->addCell("Address 2", FALSE);
    //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
    $parent_table->setCurrCellID($prefix."_addr_2_swm_td");
    $perm_addr_2_textbox='<input type="text" id="'.$prefix.'_addr_2_text">';
    $perm_addr_2_inp_cell_obj=$parent_table->addCell($perm_addr_2_textbox, FALSE);





    $location_row_obj = $parent_table->addRow();
    $parent_table->setRowID($location_row_obj, $prefix."_addr_2");
    //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
    
    $location_cell_obj = $parent_table->addCell("Location", FALSE);
    //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
    $parent_table->setCurrCellID($prefix."_location_swm_td");
    $location_textbox='<input type="text" id="'.$prefix.'_location_text">';
    $location_inp_cell_obj=$parent_table->addCell($location_textbox, FALSE);
    
    $city_row_obj=$parent_table->addRow();
    $parent_table->setRowID($city_row_obj, $prefix."_city");
    $city_cell_obj = $parent_table->addCell("Location", FALSE);
    $parent_table->setCurrCellID($prefix."_city_swm_td");
    $fetch_opt_city_list=get_master_city();
    $city_sel_elem="<select id='".$prefix."_city_list'>".$fetch_opt_city_list."</select>";
    $city_sel_cell_obj=$parent_table->addCell($city_sel_elem, FALSE);
    
    $perm_num_1_ctry_code_obj = $parent_table->addRow();
    $parent_table->setRowID($perm_num_1_ctry_code_obj, $prefix."_phone_num_1_ctry_code");
    //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
    
    $perm_phone_num_1_ctry_code_cell_obj = $parent_table->addCell(" Phone number 1 country code", FALSE);
    //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
    $parent_table->setCurrCellID($prefix."_phone_num_1_ctry_code_swm_td");
    $perm_phone_num_1_ctry_code_textbox='<input type="text" id="'.$prefix.'_phone_num_1_ctry_code_text">';
    $perm_phone_num_1_ctry_code_inp_cell_obj=$parent_table->addCell($perm_phone_num_1_ctry_code_textbox, FALSE);


    $perm_phone_num_1_obj = $parent_table->addRow();
    $parent_table->setRowID($perm_phone_num_1_obj, $prefix."_phone_num_1");
    //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
    
    $perm_phone_num_1_cell_obj = $parent_table->addCell("Phone number 1", FALSE);
    //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
    $parent_table->setCurrCellID($prefix."_phone_num_1_swm_td");
    $perm_phone_num_1_textbox='<input type="text" id="'.$prefix.'_phone_num_1_text">';
    $perm_phone_num_1_inp_cell_obj=$parent_table->addCell($perm_phone_num_1_textbox, FALSE);


    


    $perm_phone_num_2_ctry_code_obj = $parent_table->addRow();
    $parent_table->setRowID($perm_phone_num_2_ctry_code_obj, $prefix."_phone_num_2_ctry_code");
    //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
    
    $perm_phone_num_2_ctry_code_cell_obj = $parent_table->addCell(" Phone number 2 country code", FALSE);
    //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
    $parent_table->setCurrCellID($prefix."_phone_num_2_ctry_code_swm_td");
    $perm_phone_num_2_ctry_code_textbox='<input type="text" id="'.$prefix.'_phone_num_2_ctry_code_text">';
    $perm_phone_num_2_ctry_code_inp_cell_obj=$parent_table->addCell($perm_phone_num_2_ctry_code_textbox, FALSE);

    $perm_phone_num_2_obj = $parent_table->addRow();
    $parent_table->setRowID($perm_phone_num_2_obj, $prefix."_phone_num_2");
    //addAttribToElement($row_obj, '{"class":"generic_row_cls0"}');
    
    $perm_phone_num_2_cell_obj = $parent_table->addCell("Phone number 2", FALSE);
    //addAttribToElement($cell_obj, '{"class":"gen_swfm_th_cat"}');
    $parent_table->setCurrCellID($prefix."_phone_num_2_swm_td");
    $perm_phone_num_2_textbox='<input type="text" id="'.$prefix.'_phone_num_2_text">';
    $perm_phone_num_2_inp_cell_obj=$parent_table->addCell($perm_phone_num_2_textbox, FALSE);

}


function get_master_city(){
//$conn = new \mysqli($server_name, $user_name, $password, $dbname);
return "<option>server name ::::".$server_name."</option>";
// $city_query_str="select * from master_city";
// $city_prep = $conn->query($city_query_str);
// $city_result = $city_prep->get_result();
// $city_fetch_all = $city_result->fetch_all(MYSQLI_ASSOC);
// $city_option_str="";
// foreach ($city_fetch_all as $v){
// $city=$v["name"];
// $city_option_str.="<option value='".$city."'>$city</option>";
// }
// return $city_option_str;
}

function get_master_state(){
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    $state_query_str="select * from master_state";
    $state_prep = $conn->query($state_query_str);
    $state_result = $state_prep->get_result();
    $state_fetch_all = $state_result->fetch_all(MYSQLI_ASSOC);
    $state_option_str="";
    foreach ($state_fetch_all as $v){
    $state=$v["name"];
    $state_option_str.="<option value='".$state."'>$state</option>";
    }
    return $state_option_str;
}

function get_master_country(){
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    $country_query_str="select * from master_country";
    $country_prep = $conn->query($country_query_str);
    $country_result = $country_prep->get_result();
    $country_fetch_all = $country_result->fetch_all(MYSQLI_ASSOC);
    $country_option_str="";
    foreach ($country_fetch_all as $v){
    $country=$v["name"];
    $country_option_str.="<option value='".$country."'>$country</option>";
    }
    return $country_option_str;
}

?>