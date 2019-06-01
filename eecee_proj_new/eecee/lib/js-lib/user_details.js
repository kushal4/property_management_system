
$( function() {
    $( "#user_details_tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#user_details_tabs" ).on( "tabsactivate", function( event, ui ) {
    	my_tabsactivate_func( event, ui );
    } );
    $( "#user_details_tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  } );


function get_master_country(){
  
  var country_dropdown_id = $("#owner_dets_table").data("country");
  //console.log("the country dropdown ID is:: "+country_dropdown_id);
  //$("#"+country_dropdown_id).empty();
  console.log("getting inside get_master_country")
  var object = {};
  console.log(object);
  $.ajax({
    method: "POST",
    url: "get_master_country.php",
    data: { k: JSON.stringify(object) },
    dataType: "json",
    success: function (data, textStatus, jQxhr) {
      console.log("add_new_topics::AJAX Return: ");
      console.log(data);
      var status = data.session_expire;
      if (data.ret_code == 4) {
        console.log("session expired key found");
        $("#session_expire_dialog").dialog('open');
      } else {
        if(data.ret_code == 0) {
          var country_arr = data.country;
          //var option_blank = $("<option value='0' selected='selected'></option>");
          //$("#"+country_id).append(option_blank);
          $(country_arr).each(function (i, net) {
          var country_name = net.country_name;
          //console.log("the country name is:: "+ country_name);
          var country_id = net.country_id;
          //console.log("the country ID is:: "+ country_id);
          var country_option = $("<option/>");
          country_option.text(country_name);
          country_option.val(country_id);
          $("#"+country_dropdown_id).append(country_option);
        
        });
        } else {
        
        }
      }
    },
    failure: function () {
    }
  });
} 

function save_user_details(edit_elem_arr, contrlr_id){
  console.log("getting inside save_user_details")
  var object = {};
  object.edit_elem_arr = edit_elem_arr;
  object.controler_id = contrlr_id;
  console.log(object);
  $.ajax({
    method: "POST",
    url: "save_user_details_form.php",
    data: { k: JSON.stringify(object) },
    //dataType: "json",
    success: function (data, textStatus, jQxhr) {
      console.log("save_user_details::AJAX Return: ");
      console.log(data);
      var status = data.session_expire;
      if (data.ret_code == 4) {
        console.log("session expired key found");
        $("#session_expire_dialog").dialog('open');
      } else {
        if(data.ret_code == 0) {
        } else {
        
        }
      }
    },
    failure: function () {
    }
  });
}


function get_states(selected_country_val){
  
  var country_dropdown_id = $("#owner_dets_table").data("country");
  var state_dropdown_id = $("#"+country_dropdown_id).data("state");
  var cc1_dropdown_id = $("#owner_dets_table").data("code-1");
  console.log("the cc1 dropdown ID is:"+cc1_dropdown_id);
  $("#"+state_dropdown_id).empty();
  $("#"+cc1_dropdown_id).empty();
  var object = {};
  object.country_id = selected_country_val;
  console.log(object);
  $.ajax({
    method: "POST",
    url: "get_states.php",
    data: { k: JSON.stringify(object) },
    dataType: "json",
    success: function (data, textStatus, jQxhr) {
      console.log("get_states::AJAX Return: ");
      console.log(data);
      var status = data.session_expire;
      if (data.ret_code == 4) {
        console.log("session expired key found");
        $("#session_expire_dialog").dialog('open');
      } else {
        if(data.ret_code == 0) {
            var state_arr = data.state;
            var phone_code = data.phone_code;
            console.log("the phone code is" + phone_code);
            var option_blank = $("<option/>");
            $("#"+state_dropdown_id).append(option_blank);
            option_blank.text("Select State");
            $(state_arr).each(function (i, net) {
            var state_name = net.state_name;
            var state_id = net.state_id;
            var state_option = $("<option/>");
            state_option.text(state_name);
            state_option.val(state_id);
            $("#"+state_dropdown_id).append(state_option);
          
          });
          var cc1_option = $("<option/>");
          cc1_option.text(phone_code);
          $("#"+cc1_dropdown_id).append(cc1_option);
        } else {
        
        }
      }
    },
    failure: function () {
    }
  });
}


function get_cities(selected_state_val, state_dropdown_id){
  
  var city_dropdown_id = $("#"+state_dropdown_id).data("city");
  console.log("the city dropdown ID is:: "+city_dropdown_id);
  console.log("getting inside get_cities function")
  $("#"+city_dropdown_id).empty();
  var object = {};
  object.state_id = selected_state_val;
  console.log(object);
  $.ajax({
    method: "POST",
    url: "get_cities.php",
    data: { k: JSON.stringify(object) },
    dataType: "json",
    success: function (data, textStatus, jQxhr) {
      console.log("get_states::AJAX Return: ");
      console.log(data);
      var status = data.session_expire;
      if (data.ret_code == 4) {
        console.log("session expired key found");
        $("#session_expire_dialog").dialog('open');
      } else {
        if(data.ret_code == 0) {
            var city_arr = data.city;
            var option_blank = $("<option/>");
            $("#"+city_dropdown_id).append(option_blank);
            option_blank.text("Select City");
            $(city_arr).each(function (i, net) {
            var city_name = net.city_name;
            console.log("the state name is:: "+ city_name);
            
            var city_id = net.city_id;
            console.log("the state ID is:: "+ city_id);
            var city_option = $("<option/>");
            city_option.text(city_name);
            city_option.val(city_id);
            
            $("#"+city_dropdown_id).append(city_option);
          
          });
        } else {
        
        }
      }
    },
    failure: function () {
    }
  });
}



function selected_country(country_id){
  var selected_country_val = $("#"+country_id).val();
  console.log("the value of the selected country is:: "+selected_country_val);
  get_states(selected_country_val);
  
}

function selected_state(state_dropdown_id){
  var selected_state_val = $("#"+state_dropdown_id).val();
  console.log("the value of the selected state is:: "+selected_state_val);
  get_cities(selected_state_val, state_dropdown_id);
}


function body_load_func() {
  console.log("getting inside user_details body_load_func");
}


$(document).ready(function(){
  $("#user_dets_dialog").dialog({
    width: 700,
    height: 300,
    dialogClass: 'generic_dialog',
    autoOpen: false,
    modal: true,
    close: function () {
      
    },

  });	

  console.log("user_details ready");
  get_master_country();  

  var country_id = $("#owner_dets_table").data("country");
  console.log("the country dropdown ID is:: "+country_id);

  var state_id = $("#"+country_id).data("state");

  $("#"+country_id).change(function (ev) {
      selected_country(country_id);
  });
  
  $("#"+state_id).change(function (ev) {
    selected_state(state_id);
  });

  $(this).on('click',function(e){
    var input = e.target;
    var input_id = input.id;
    var fields = input_id.split('-');
    var name = fields[0];
    var id = fields[1];

    var create_pt = ".pt-"+id;
    var create_lbl_pt = ".lbl-pt-"+id;

    var edit_id = "#edit-"+id;
    var save_id = "#save-"+id;

    var data_state = $(input).parent().data("s");
    console.log("the state is:: "+data_state);

    if(data_state == "e"){
      $(".pc").hide();
      $(save_id).show();
      $(edit_id).hide();
      $(input).parent().show();
      $(create_pt).show();
      $(create_lbl_pt).hide();
      $(input).parent().data("s", "s");
    } else if(data_state == "s"){

      var editing_elems = document.getElementsByClassName("pt-"+id);
      console.log(editing_elems);
      var edit_elem_arr = [];
      $(editing_elems).each(function (k, v) {
      
        var editing_elems_id = v.id;
        //console.log("the ID of the elements are:: "+editing_elems_id);
        var editing_elem_value = $("#"+editing_elems_id).val();
        //console.log("the value of the elements are:: "+editing_elem_value);
        var abc = { "input_id" : editing_elems_id , "input_val" : editing_elem_value};
        edit_elem_arr.push(abc);
      });
      save_user_details(edit_elem_arr, id);
      $(save_id).hide();
      $(edit_id).show();
      $(".pc").show();
      $(create_lbl_pt).show();
      $(create_pt).hide();
      $(input).parent().data("s", "e");
    }   
});

});