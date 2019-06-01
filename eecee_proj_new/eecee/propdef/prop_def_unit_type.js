function test_func(){
    //alert("aaaa");
    console.log("getting inside test_func");
    $("#add_unit_table").tablecreator("clearTable");
    var unit_name = "Unit Type Name";
    var tr = $("<tr/>");
    var td1 = $("<td/>");
    var td2 = $("<td/>");
    var textbox = $("<input/>");
    textbox.attr('id', 'usr_typ_nm_txtbx');

    textbox.addClass("add_unit_txtbox_style");
    var role_row_id = Math.floor(Math.random() * 100000);
    tr.addClass("user_name_col_style");
    td1.addClass("col1_cls");
    
    td1.html(unit_name);
    td2.append(textbox);

    tr.append(td1);
    tr.append(td2);
    $("#add_unit_table").tablecreator("createRow", { sig: "a-" + role_row_id, tablerow: tr });
    console.log("getting inside test_func 2");
}

/*
function call_edit_unit_type(textbox_value){
    console.log("getting inside call_edit_unit_type");
	var object = {};
	object.unit_type_name = textbox_value;
	$.ajax({
		method: "POST",
		url: "edit_unit_type.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("edit_unit_type::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
                    var unit_type_name = data.unit_type_name;
                    
					$("#main_container").load("unit_type_edit.php #main_cont",function(){
                        $("#edit_feature_tbl_cont").load("unit_fea_list.php",function(){
                            console.log("the unit type name is :: "+unit_type_name);
                            $('#unit_type_name_span_val').text(unit_type_name);
                            $("#unit_type_name_txtbox").attr("disabled", "disabled"); 
                            $('.add_unit_feature_btn_div_style').show();

                            $("#edit_btn_cont").on('click',function(e){
                                console.log("clicked on EDIT");
                                edit_unit_type_name();
                            });

                            $("#add_unit_feature_plus_cont").on('click',function(e){
                                //alert("aaa");
                                console.log("clicked on +");
                                $("#add_feat_dialog").dialog('open');
                            });
            
                            $("#add_feat_dialog").on( "dialogopen", function( event, ui ) {
                                //alert("on dialog open");
                                fill_features_in_dropdown();
                            });
                        });
                    });
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}
*/

/*
function edit_unit_type(textbox_value, op_val){
    console.log("getting inside edit_unit_type");
    var object = {};
    object.op = op_val;
    //if(op_val == "s"){
        object.unit_type_name = textbox_value;
    //}
    $("#main_container").load( "unit_type_edit.php #main_cont", object, function() {
        
        $("#edit_feature_tbl_cont").load("unit_fea_list.php",function(){
            console.log("the unit type name is :: "+textbox_value);
            $('#unit_type_name_span_val').text(textbox_value);
            $("#unit_type_name_txtbox").attr("disabled", "disabled"); 
            $('.add_unit_feature_btn_div_style').show();

            $("#save_btn_cont").on('click',function(e){
                console.log("clicked on EDIT");
                var op_val = $("#save_btn_cont").attr("op");
                console.log(op_val);
                var span_text = $("#unit_type_name_span_val").text();
                console.log(span_text);
                //$('.add_unit_feature_btn_div_style').show();
                $("#edit_feature_tbl_cont").load("feat_list_tbl.php #main_cont",function(){
                    //$("#add_feat_dialog").dialog('close');
                    alert("edit table");
                });
                edit_unit_type(span_text, op_val);
            });

            $("#add_unit_feature_plus_cont").on('click',function(e){
                //alert("aaa");
                console.log("clicked on +");
                $("#add_feat_dialog").dialog('open');
            });

            $("#add_feat_dialog").on( "dialogopen", function( event, ui ) {
                //alert("on dialog open");
                fill_features_in_dropdown();
            });
        });
        
    });
}
*/




function call_add_this_feature(selected_feature_val, selected_feature_txt){
    console.log("getting inside call_add_this_feature");
	var object = {};
    object.feat_val = selected_feature_val;
    object.feat_text = selected_feature_txt;
	$.ajax({
		method: "POST",
		url: "call_add_this_feature.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("call_add_this_feature::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
                    console.log("date::  call_add_this_feature");
					//$("#main_container").load("unit_type_edit.php #main_cont",function(){
                        var feat_tbl_object = {};
                        feat_tbl_object.op = "a";
                        $("#edit_feature_tbl_cont").load("feat_list_tbl.php #main_cont", feat_tbl_object, function(){ // after adding a feature 
                            $("#add_feat_dialog").dialog('close');
                            $(".delete_feature_style").on('click',function(e){
                                console.log("clicked on featur delete");
                                console.log("clicked on update unit type name");
                                console.log(e);
                                console.log(e.target.dataset.ut);
                                var del_feat_id = e.target.dataset.ut;
                                console.log(del_feat_id);
                                $("#feat_delete_dialog").data("del_feat_id",del_feat_id);
                                $("#feat_delete_dialog").dialog('open');
                            });

                            $("#add_unit_feature_plus_cont").on('click',function(e){
                                console.log("clicked on +");
                                $("#add_feat_dialog").dialog('open');   
                            });


                            $("#add_atr_btn_cont").off('click');
                            $("#add_atr_btn_cont").on('click',function(e){
                                console.log("clicked on plus button");
                                console.log(e);
                                console.log($(this));
                                var feat_id = ($(this).data("ut"));
                                console.log(feat_id);
                                //this.hhjj;
                                $(this).data("ut");
                                //console.log("here");
                                $("#add_attrib_dialog").data("feat_id",feat_id);
                                //$("#add_attrib_dialog").data("type","l");
                                $("#add_attrib_dialog").dialog('open');
                            });

                            $(".active_name_style").off('click');
                            $(".active_name_style").on('click',function(e){
                                console.log("clicked on attribute");
                                console.log(e);
                                console.log($(this));
                                var attrib_id = ($(this).data("att"));
                                console.log("attrib_id from attribute table:: "+attrib_id);
                                var fea_attrib_id = ($(this).data("f_att"));
                                console.log("unit_fea_attrib:: "+fea_attrib_id);
                                $("#add_attrib_dialog").data("type","f");
                                $("#add_attrib_dialog").data("fea_attrib_id",fea_attrib_id);
                                $("#add_attrib_dialog").data("attrib_id",attrib_id);
                                $("#add_attrib_dialog").dialog('open');
                            });

                            $( ".attrib_accordion_style" ).accordion({
                                collapsible: true,
                                active: false,
                                heightStyle:"content",
                              });


                        });   
                    //});
                    //$("#add_feat_dialog").dialog('close');
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function call_add_this_attribute(selected_attrib_val, selected_atrib_txt){
    console.log("getting inside call_add_this_attribute");
    var feat_id = $("#add_attrib_dialog").data("feat_id");
	var object = {};
    object.attrib_val = selected_attrib_val;
    object.attrib_text = selected_atrib_txt;
    object.unit_feat_id = feat_id;
	$.ajax({
		method: "POST",
		url: "call_add_this_attribute.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("call_add_this_attribute::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
                    console.log("date::  call_add_this_attribute");
                        
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function click_on_save_edit_btn(){
    console.log("clicked on save");
    var op_val = $("#save_btn_cont").attr("op");
    console.log(op_val);
    var textbox_value = $("#unit_type_name_span_val").val();
    console.log(textbox_value);
    if (op_val == "e"){
        textbox_value = $("#unit_type_name_span_val").text();
        console.log(textbox_value);
    }
    if(textbox_value == ""){
        console.log("the textbox value is null");
    }else{
        console.log("the textbox value is not null");
        var object = {};
        object.op = op_val;
        //if(op_val == "s"){
            object.unit_type_name = textbox_value;
        //}
        $("#add_unit_type_name_cont").load( "unit_type_edit.php #unit_type_edit", object, function() {
            /*
            $("#add_feat_dialog").dialog({
                width: 500,
                height: 150,
                dialogClass: 'generiDefinition_dialog',
                autoOpen: false,
                modal: true,
                close: function () {
                },
            });	
            */
            $("#add_unit_feature_cont").load( "unit_type_edit.php #add_unit_feature_btn_div", object, function() {
                $("#save_btn_cont").on('click',function(e){
                    click_on_save_edit_btn();
                });
                $("#add_unit_feature_plus_cont").on('click',function(e){
                    console.log("clicked on +");
                    $("#add_feat_dialog").dialog('open');   
                });
            });
            $("#add_feat_btn_cont").off('click');
            $("#add_feat_btn_cont").on('click',function(e){
                
                
                console.log("clicked on add_feat_btn_cont");
                var selected_feature_val = $("#feat_dropdown").val();
                var selected_feature_txt = $("#feat_dropdown option:selected").text();
                console.log(selected_feature_val);
                console.log(selected_feature_txt);
                if(selected_feature_val == 0 && selected_feature_txt == "Select Feature"){
        
                }else{
                    call_add_this_feature(selected_feature_val, selected_feature_txt);
                }
                
            });
        });
    }
}


function delete_unit_type_name(unit_type_id){
    console.log("getting inside delete_unit_type_name");
    var object = {};
    object.unit_type_id = unit_type_id;
	$.ajax({
		method: "POST",
		url: "delete_unit_type_name.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("delete_unit_type_name::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					call_prop_def_unit_type_list();
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function delete_feature(del_feat_id){
    console.log("getting inside delete_feature");
    var object = {};
    object.del_feat_id = del_feat_id;
	$.ajax({
		method: "POST",
		url: "delete_feature.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("delete_feature::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
                    //call_prop_def_unit_type_list();
                    var feat_tbl_object = {};
                    feat_tbl_object.op = "d";
                    $("#edit_feature_tbl_cont").load("feat_list_tbl.php #main_cont", feat_tbl_object, function(){ // after deleting feature
                        $("#feat_delete_dialog").dialog('close');
                        $(".delete_feature_style").on('click',function(e){
                            console.log("clicked on featur delete");
                            console.log("clicked on update unit type name");
                            console.log(e);
                            console.log(e.target.dataset.ut);
                            var del_feat_id = e.target.dataset.ut;
                            console.log(del_feat_id);
                            $("#feat_delete_dialog").data("del_feat_id",del_feat_id);
                            $("#feat_delete_dialog").dialog('open');
                            
                        });

                        $("#add_unit_feature_plus_cont").on('click',function(e){
                            console.log("clicked on +");
                            $("#add_feat_dialog").dialog('open');   
                        });


                        $(".add_atr_btn_cont_style").off('click');
                        $(".add_atr_btn_cont_style").on('click',function(e){
                            console.log("clicked on plus button");
                            console.log(e);
                            console.log($(this));
                            var feat_id = ($(this).data("ut"));
                            console.log(feat_id);
                            //this.hhjj;
                            $(this).data("ut");
                            //console.log("here");
                            $("#add_attrib_dialog").data("feat_id",feat_id);
                            //$("#add_attrib_dialog").data("type","l");
                            $("#add_attrib_dialog").dialog('open');
                        });

                        $(".active_name_style").off('click');
                        $(".active_name_style").on('click',function(e){
                            console.log("clicked on attribute");
                            console.log(e);
                            console.log($(this));
                            var attrib_id = ($(this).data("att"));
                            console.log("attrib_id from attribute table:: "+attrib_id);
                            var fea_attrib_id = ($(this).data("f_att"));
                            console.log("unit_fea_attrib:: "+fea_attrib_id);
                            $("#add_attrib_dialog").data("type","f");
                            $("#add_attrib_dialog").data("fea_attrib_id",fea_attrib_id);
                            $("#add_attrib_dialog").data("attrib_id",attrib_id);
                            $("#add_attrib_dialog").dialog('open');
                        });

                        $( ".attrib_accordion_style" ).accordion({
                            collapsible: true,
                            active: false,
                            heightStyle:"content",
                          });
                    });   
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function call_prop_def_unit_type_list(){
    $("#main_container").load("unit_type_list.php #main_cont",function(){
        $("#create_btn_cont").on('click',function(e){
           $("#main_container").load("unit_type_edit_structure.php #main_cont",function(){
                var object = {};
                object.op = "l";
                $("#add_unit_type_name_cont").load( "unit_type_edit.php #main_cont", object, function() {
                    $("#save_btn_cont").on('click',function(e){
                        click_on_save_edit_btn();
                        
                    });
                });
            });   
        });

        $(".delete_span_class").on('click',function(e){
            console.log("clicked on delete unit type name");
            console.log(e);
            console.log(e.target.dataset.ut);
            var unit_type_id = e.target.dataset.ut;
            $("#unit_type_delete_dialog").data("unit_type_id",unit_type_id);
            $("#unit_type_delete_dialog").dialog('open');
            
        });

        $(".update_span_class").on('click',function(e){
            console.log("clicked on update unit type name");
            console.log(e);
            console.log(e.target.dataset.ut);
            var unit_type_id = e.target.dataset.ut;
            //edit_unit_type_name(unit_type_id);
            //$("#unit_type_update_dialog").data("unit_type_id",unit_type_id);
            //$("#unit_type_update_dialog").dialog('open');
            /*
            $("#add_feat_dialog").dialog({
                width: 500,
                height: 150,
                dialogClass: 'generiDefinition_dialog',
                autoOpen: false,
                modal: true,
                close: function () {
                },
                open: function (){
                    fill_features_in_dropdown();
                }
            });
            */

            $("#main_container").load("unit_type_edit_structure.php #main_cont",function(){
                
                var object = {};
                object.op = "u";
                object.unit_type_id = unit_type_id;
                
                
                $("#add_unit_type_name_cont").load( "unit_type_edit.php #main_cont", object, function() {
                    
                    $("#save_btn_cont").on('click',function(e){
                        click_on_save_edit_btn();
                    });
                    /*
                    $("#add_unit_feature_plus_cont").on('click',function(e){
                        console.log("clicked on +");
                        $("#add_feat_dialog").dialog('open');
                    });
        
                    $("#add_feat_btn_cont").off('click');
                    $("#add_feat_btn_cont").on('click',function(e){
                        
                        
                        console.log("clicked on add_feat_btn_cont");
                        var selected_feature_val = $("#feat_dropdown").val();
                        var selected_feature_txt = $("#feat_dropdown option:selected").text();
                        console.log(selected_feature_val);
                        console.log(selected_feature_txt);
                        if(selected_feature_val == 0 && selected_feature_txt == "Select Feature"){
                
                        }else{
                            call_add_this_feature(selected_feature_val, selected_feature_txt);
                        }
                        
                    });
                    */
                });
                
                
                
                var feat_tbl_object = {};
                feat_tbl_object.op = "u";
                $("#edit_feature_tbl_cont").load("feat_list_tbl.php #main_cont", feat_tbl_object, function(){ // once we click on the update button of the unit type table
                    //$("#feat_delete_dialog").dialog('close');
                    $(".delete_feature_style").on('click',function(e){
                        console.log("clicked on featur delete");
                        console.log("clicked on update unit type name");
                        console.log(e);
                        console.log(e.target.dataset.ut);
                        var del_feat_id = e.target.dataset.ut;
                        console.log(del_feat_id);
                        $("#feat_delete_dialog").data("del_feat_id",del_feat_id);
                        $("#feat_delete_dialog").dialog('open');
                        
                    });

                    $("#add_unit_feature_plus_cont").on('click',function(e){
                        console.log("clicked on +");
                        $("#add_feat_dialog").dialog('open');   
                    });
                    
                    $(".add_atr_btn_cont_style").off('click');
                    $(".add_atr_btn_cont_style").on('click',function(e){
                            console.log("clicked on plus button");
                            console.log(e);
                            console.log($(this));
                            var feat_id = ($(this).data("ut"));
                            console.log(feat_id);
                            //this.hhjj;
                            $(this).data("ut");
                            //console.log("here");
                            $("#add_attrib_dialog").data("feat_id",feat_id);
                            //$("#add_attrib_dialog").data("type","l");
                            $("#add_attrib_dialog").dialog('open');
                    });


                    $(".active_name_style").off('click');
                    $(".active_name_style").on('click',function(e){
                        console.log("clicked on attribute");
                        console.log(e);
                        console.log($(this));
                        var attrib_id = ($(this).data("att"));
                        console.log("attrib_id from attribute table:: "+attrib_id);
                        var fea_attrib_id = ($(this).data("f_att"));
                        console.log("unit_fea_attrib:: "+fea_attrib_id);
                        $("#add_attrib_dialog").data("type","f");
                        $("#add_attrib_dialog").data("fea_attrib_id",fea_attrib_id);
                        $("#add_attrib_dialog").data("attrib_id",attrib_id);
                        $("#add_attrib_dialog").dialog('open');

                    });

                    $( ".attrib_accordion_style" ).accordion({
                        collapsible: true,
                        active: false,
                        heightStyle:"content",
                      });
                    /*
                    $("#add_attrb_btn_cont_style").off('click');
                    $("#add_attrb_btn_cont_style").on('click',function(e){
                        
                        
                        console.log("clicked on add_attrb_btn_cont_style");
                        var selected_attrib_val = $("#attrb_dropdown").val();
                        var selected_atrib_txt = $("#attrb_dropdown option:selected").text();
                        console.log(selected_attrib_val);
                        console.log(selected_atrib_txt);
                        if(selected_attrib_val == 0 && selected_atrib_txt == "Select Feature"){
                
                        }else{
                            call_add_this_attribute(selected_attrib_val, selected_atrib_txt);
                        }
                        
                    });
                    */
                    
                });  
            });
        });
        
    });
    
    //$("#create_btn_cont").click(func_test);
}

function unit_type_update(unit_type_id){
    console.log("getting inside unit_type_update");
    $("#main_container").load("unit_type_edit_structure.php #main_cont",function(){
        var object = {};
        object.op = "u";
        object.unit_type_id = unit_type_id;
        $("#add_unit_type_name_cont").load( "unit_type_edit.php #main_cont", object, function() {
            $("#save_btn_cont").on('click',function(e){
                click_on_save_edit_btn();
            });

            $("#add_unit_feature_plus_cont").on('click',function(e){
                //alert("aaa");
                console.log("clicked on +");
                $("#add_feat_dialog").dialog('open');
            });

            $("#add_feat_btn_cont").off('click');
            $("#add_feat_btn_cont").on('click',function(e){
                
                
                console.log("clicked on add_feat_btn_cont");
                var selected_feature_val = $("#feat_dropdown").val();
                var selected_feature_txt = $("#feat_dropdown option:selected").text();
                console.log(selected_feature_val);
                console.log(selected_feature_txt);
                if(selected_feature_val == 0 && selected_feature_txt == "Select Feature"){
        
                }else{
                    call_add_this_feature(selected_feature_val, selected_feature_txt);
                }
                
            });
        });
    });
}

function features_dropdown(features){
    $(features).each(function (i, index) {
        var feature_id = index.feature_id;
        var feature_name = index.feature_name;

        var option_val = $("<option/>");
		option_val.text(feature_name);
		option_val.val(feature_id);
		$('#feat_dropdown').append(option_val);
		
	});
}

function fill_features_in_dropdown(){
    console.log("getting inside fill_features_in_dropdown");
    $('#feat_dropdown').empty();
	var option_blank = $("<option value='0' selected='selected'></option>");
	option_blank.text("Select Feature");
	$('#feat_dropdown').append(option_blank);
	var object = {};
	$.ajax({
		method: "POST",
		url: "fill_features_in_dropdown.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("fill_features_in_dropdown::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
                    var features = data.features;
                    console.log(features);
                    features_dropdown(features);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function attributes_dropdown(attributes){
    $(attributes).each(function (i, index) {
        var attrib_id = index.attrib_id;
        var attrib_name = index.attrib_name;
        var option_val = $("<option/>");
		option_val.text(attrib_name);
		option_val.val(attrib_id);
		$('#attrb_dropdown').append(option_val);
		
	});
}

function fill_attibutes_in_dropdown(){
    console.log("getting inside fill_attibutes_in_dropdown function");
    $('#attrb_dropdown').empty();
	var option_blank = $("<option value='0' selected='selected'></option>");
	option_blank.text("Select Attribute");
	$('#attrb_dropdown').append(option_blank);
	var object = {};
	$.ajax({
		method: "POST",
		url: "fill_attibutes_in_dropdown.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("fill_attibutes_in_dropdown::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
                    var attributes = data.attributes;
                    console.log(attributes);
                    attributes_dropdown(attributes);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function insert_in_unit_fea_attrib_tbl(feat_id, attr_id, attrib_value){
    console.log("getting inside insert_in_unit_fea_attrib_tbl function");
    var object = {};
    object.feat_id = feat_id;
    object.attr_id = attr_id;
    object.attrib_value = attrib_value;
	$.ajax({
		method: "POST",
		url: "insert_in_unit_fea_attrib_tbl.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("insert_in_unit_fea_attrib_tbl::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}


$(document).ready(function(){
    //alert("nnn");

    
    $("#add_feat_dialog").dialog({
        width: 500,
        height: 150,
        dialogClass: 'generiDefinition_dialog',
        autoOpen: false,
        modal: true,
        close: function () {
        },
        open: function (){
            fill_features_in_dropdown();
        },
        create:function(){
            //alert("create");
        }
    });

    $("#add_attrib_dialog").dialog({
        width: 500,
        height: 250,
        dialogClass: 'generiDefinition_dialog',
        autoOpen: false,
        modal: true,
        close: function () {
        },
        open: function (){

            console.log($(this));
            var type = $("#add_attrib_dialog").data("type");
            console.log("the dialog open type :: "+type);

            if(type != undefined){
                if(type == "f"){
                    var fea_attrib_id = $("#add_attrib_dialog").data("fea_attrib_id");
                    console.log("the fea_attrib_id :: "+fea_attrib_id);
                    var attrib_id = $("#add_attrib_dialog").data("attrib_id");
                    console.log("the attrib_id :: "+attrib_id);
                    var object = {};
                    object.op = "f";
                    object.fea_attrib_id = fea_attrib_id;
                    object.attrib_id = attrib_id;
                    $("#add_attrib_dia_cont").load("add_attribute_dialog_view.php #main_cont", object, function(data){
                        var upd_val = "";
                        $(".attrb_val_dd_style").change(function(){
                            console.log("changed orientation");
                            selected_mas_opt_val = $("#attrb_val_dd").val();
                            console.log("the selected selected_mas_opt_val value is :: "+selected_mas_opt_val);
                        }); 
                        $(".attrb_update_btn_cont_style").off('click');
                        $(".attrb_update_btn_cont_style").on('click',function(e){

                            var attribute_name = $("#attrb_name_cont").text();
                            console.log("the attribute_name is:: "+attribute_name);

                            if(attribute_name == "Vastu Orientation" || attribute_name == "Floor Type" || attribute_name == "Parking Type" || attribute_name == "Floor"){
                                
                                upd_val = selected_mas_opt_val;
                                console.log("the updated value is:: "+upd_val);
                            }else{
                                upd_val = $("#attrb_value_textbox").val();
                                console.log("the updated value is:: "+upd_val);
                            }

                            
                            
                            var obj = {};
                            obj.op = "w";
                            obj.fea_attrib_id = fea_attrib_id;
                            obj.upd_val = upd_val;
                            obj.attrib_id = attrib_id;
                            
                            $("#add_attrib_dia_cont").load("add_attribute_dialog_view.php #main_cont", obj, function(data){
                                hidden_val = $("#hidden_elem_cont").val();
                                console.log(hidden_val);
                                if(hidden_val == "0"){
                                    var feat_tbl_object = {};
                                    feat_tbl_object.op = "a";
                                    $("#edit_feature_tbl_cont").load("feat_list_tbl.php #main_cont", feat_tbl_object, function(){ // after deleting feature
                                        $("#feat_delete_dialog").dialog('close');
                                        $(".delete_feature_style").on('click',function(e){
                                            console.log("clicked on featur delete");
                                            console.log("clicked on update unit type name");
                                            console.log(e);
                                            console.log(e.target.dataset.ut);
                                            var del_feat_id = e.target.dataset.ut;
                                            console.log(del_feat_id);
                                            $("#feat_delete_dialog").data("del_feat_id",del_feat_id);
                                            $("#feat_delete_dialog").dialog('open');
                                            
                                        });

                                        $("#add_unit_feature_plus_cont").on('click',function(e){
                                            console.log("clicked on +");
                                            $("#add_feat_dialog").dialog('open');   
                                        });

                                        $(".add_atr_btn_cont_style").off('click');
                                        $(".add_atr_btn_cont_style").on('click',function(e){
                                            console.log("clicked on plus button");
                                            console.log(e);
                                            console.log($(this));
                                            var feat_id = ($(this).data("ut"));
                                            console.log(feat_id);
                                            //this.hhjj;
                                            $(this).data("ut");
                                            //console.log("here");
                                            $("#add_attrib_dialog").data("feat_id",feat_id);
                                            //$("#add_attrib_dialog").data("type","l");
                                            $("#add_attrib_dialog").dialog('open');
                                        });

                                        $(".active_name_style").off('click');
                                        $(".active_name_style").on('click',function(e){
                                            console.log("clicked on attribute");
                                            console.log(e);
                                            console.log($(this));
                                            var attrib_id = ($(this).data("att"));
                                            console.log("attrib_id from attribute table:: "+attrib_id);
                                            var fea_attrib_id = ($(this).data("f_att"));
                                            console.log("unit_fea_attrib:: "+fea_attrib_id);
                                            $("#add_attrib_dialog").data("type","f");
                                            $("#add_attrib_dialog").data("fea_attrib_id",fea_attrib_id);
                                            $("#add_attrib_dialog").data("attrib_id",attrib_id);
                                            $("#add_attrib_dialog").dialog('open');
                                        });

                                        $( ".attrib_accordion_style" ).accordion({
                                            collapsible: true,
                                            active: false,
                                            heightStyle:"content",
                                        });
                                    });  
                                    $("#add_attrib_dialog").dialog('close');
                                }
                            });
                            

                        });

                        $(".attrb_delete_btn_cont_style").off('click');
                        $(".attrb_delete_btn_cont_style").on('click',function(e){
                            $("#add_attrib_dialog_cont").hide();
                            //$("#add_attrib_dialog_cont").css("display", "none");
                            $("#del_attrib_cont").show();
                        });

                        $("#del_no_cont").off('click');
                        $("#del_no_cont").on('click',function(e){
                            $("#add_attrib_dialog_cont").show();
                            $("#del_attrib_cont").hide();
                        });

                        $("#del_yes_cont").off('click');
                        $("#del_yes_cont").on('click',function(e){
                           console.log("yes yes yes!");
                           $("#add_attrib_dialog").dialog('close');
                           var obj1 = {};
                           obj1.op = "del";
                           obj1.fea_attrib_id = fea_attrib_id;
                           //object.attrib_id = attrib_id;
                           console.log(obj1);
                           $("#add_attrib_dia_cont").load("add_attribute_dialog_view.php #main_cont", obj1, function(data){
                              //console.log(data);
                              hidden_val = $("#hidden_elem_cont").val();
                              console.log(hidden_val);
                              if(hidden_val == "0"){
                                var feat_tbl_object = {};
                                feat_tbl_object.op = "a";
                                $("#edit_feature_tbl_cont").load("feat_list_tbl.php #main_cont", feat_tbl_object, function(){ // after deleting feature
                                    $("#feat_delete_dialog").dialog('close');
                                    $(".delete_feature_style").on('click',function(e){
                                        console.log("clicked on featur delete");
                                        console.log("clicked on update unit type name");
                                        console.log(e);
                                        console.log(e.target.dataset.ut);
                                        var del_feat_id = e.target.dataset.ut;
                                        console.log(del_feat_id);
                                        $("#feat_delete_dialog").data("del_feat_id",del_feat_id);
                                        //$("#feat_delete_dialog").dialog('open');
                                        
                                    });

                                    $("#add_unit_feature_plus_cont").on('click',function(e){
                                        console.log("clicked on +");
                                        $("#add_feat_dialog").dialog('open');   
                                    });

                                    $(".add_atr_btn_cont_style").off('click');
                                    $(".add_atr_btn_cont_style").on('click',function(e){
                                        console.log("clicked on plus button");
                                        console.log(e);
                                        console.log($(this));
                                        var feat_id = ($(this).data("ut"));
                                        console.log(feat_id);
                                        //this.hhjj;
                                        $(this).data("ut");
                                        //console.log("here");
                                        $("#add_attrib_dialog").data("feat_id",feat_id);
                                        //$("#add_attrib_dialog").data("type","l");
                                        $("#add_attrib_dialog").dialog('open');
                                    });

                                    $(".active_name_style").off('click');
                                    $(".active_name_style").on('click',function(e){
                                        console.log("clicked on attribute");
                                        console.log(e);
                                        console.log($(this));
                                        var attrib_id = ($(this).data("att"));
                                        console.log("attrib_id from attribute table:: "+attrib_id);
                                        var fea_attrib_id = ($(this).data("f_att"));
                                        console.log("unit_fea_attrib:: "+fea_attrib_id);
                                        $("#add_attrib_dialog").data("type","f");
                                        $("#add_attrib_dialog").data("fea_attrib_id",fea_attrib_id);
                                        $("#add_attrib_dialog").data("attrib_id",attrib_id);
                                        $("#add_attrib_dialog").dialog('open');
                                    });

                                    $( ".attrib_accordion_style" ).accordion({
                                        collapsible: true,
                                        active: false,
                                        heightStyle:"content",
                                    });
                                });  
                              }
                           });


                        });

                    });
                }
            }
            else{

                var object = {};
                object.op = "l";
                $("#add_attrib_dia_cont").load("add_attribute_dialog_view.php #main_cont", object, function(data){
                    //console.log(data);
                    //fill_attibutes_in_dropdown();
                    $(".attrb_dropdown_style").change(function(){
                        console.log("the attribute dropdown has changed!");
                        var selected_attr_val = $("#attrb_dropdown").val();
                        var selected_mas_opt_val = "";
                        //var selected_attr_txt = $("#attrb_dropdown option:selected").text();
                        //console.log("the selected attribute text is:: "+selected_attr_txt);
                        var object = {};
                        object.op = "d";
                        object.attrib_id = selected_attr_val;
                        $("#add_attrib_dia_cont").load("add_attribute_dialog_view.php #main_cont", object, function(data){
                            //fill_attibutes_in_dropdown();
                            //$('#add_attrib_dia_cont option[value="no"]').attr("selected", "selected");

                            $(".attrb_val_dd_style").change(function(){
                                //var selected_mas_opt_val = $("#attrb_val_dd").val();
                                //console.log("the selected selected_mas_opt_val value is :: "+selected_mas_opt_val);
                                console.log("changed orientation");
                                selected_mas_opt_val = $("#attrb_val_dd").val();
                                console.log("the selected selected_mas_opt_val value is :: "+selected_mas_opt_val);
                            }); 

                            $(".add_attrb_btn_cont_style").on('click',function(e){
                                console.log("clicked on add this attribute");
                                var selected_attr_val = $("#attrb_dropdown").val();
                                var selected_attr_txt = $("#attrb_dropdown option:selected").text();
                                console.log("the selected attribute value is"+selected_attr_val);
                                //console.log("the selected attribute text is"+selected_attr_txt);
                                 
                                

                                var feat_id = $("#add_attrib_dialog").data("feat_id");
                                //var feat_id = $("#add_attrib_dialog").data("type");
                                console.log("the feature ID is :: "+feat_id);
                                var attrib_value = $(".attrb_val_txtbox_style").val();
                                console.log("the attrib_value is :: "+attrib_value);
                                var obj = {};
                                obj.feat_id = feat_id;
                                obj.attrib_id = selected_attr_val;
                                obj.attrib_value = attrib_value;
                                if(selected_attr_txt == "Vastu Orientation" || selected_attr_txt == "Floor Type" || selected_attr_txt == "Parking Type" || selected_attr_txt == "Floor"){
                                    obj.mas_opt_val = selected_mas_opt_val;
                                    obj.attrib_value = "";
                                }
                                
                                obj.op = "c";
                                
                                $("#add_attrib_dia_cont").load("add_attribute_dialog_view.php #main_cont", obj, function(data){
                                    hidden_val = $("#hidden_elem_cont").val();
                                    console.log(hidden_val);
                                    if(hidden_val == "0"){
                                        var feat_tbl_object = {};
                                        feat_tbl_object.op = "a";
                                        $("#edit_feature_tbl_cont").load("feat_list_tbl.php #main_cont", feat_tbl_object, function(){ // after deleting feature
                                            $("#feat_delete_dialog").dialog('close');
                                            $(".delete_feature_style").on('click',function(e){
                                                console.log("clicked on featur delete");
                                                console.log("clicked on update unit type name");
                                                console.log(e);
                                                console.log(e.target.dataset.ut);
                                                var del_feat_id = e.target.dataset.ut;
                                                console.log(del_feat_id);
                                                $("#feat_delete_dialog").data("del_feat_id",del_feat_id);
                                                $("#feat_delete_dialog").dialog('open');
                                                
                                            });

                                            $("#add_unit_feature_plus_cont").on('click',function(e){
                                                console.log("clicked on +");
                                                $("#add_feat_dialog").dialog('open');   
                                            });


                                            $(".add_atr_btn_cont_style").off('click');
                                            $(".add_atr_btn_cont_style").on('click',function(e){
                                                console.log("clicked on plus button");
                                                console.log(e);
                                                console.log($(this));
                                                var feat_id = ($(this).data("ut"));
                                                console.log(feat_id);
                                                //this.hhjj;
                                                $(this).data("ut");
                                                //console.log("here");
                                                $("#add_attrib_dialog").data("feat_id",feat_id);
                                                //$("#add_attrib_dialog").data("type","l");
                                                $("#add_attrib_dialog").dialog('open');
                                            });

                                            $(".active_name_style").off('click');
                                            $(".active_name_style").on('click',function(e){
                                                console.log("clicked on attribute");
                                                console.log(e);
                                                console.log($(this));
                                                var attrib_id = ($(this).data("att"));
                                                console.log("attrib_id from attribute table:: "+attrib_id);
                                                var fea_attrib_id = ($(this).data("f_att"));
                                                console.log("unit_fea_attrib:: "+fea_attrib_id);
                                                $("#add_attrib_dialog").data("type","f");
                                                $("#add_attrib_dialog").data("fea_attrib_id",fea_attrib_id);
                                                $("#add_attrib_dialog").data("attrib_id",attrib_id);
                                                $("#add_attrib_dialog").dialog('open');
                                            });

                                            $( ".attrib_accordion_style" ).accordion({
                                                collapsible: true,
                                                active: false,
                                                heightStyle:"content",
                                            });
                                        });  
                                        $("#add_attrib_dialog").dialog('close');
                                    } 
                                    
                                });   
                                
                            
                            });
                            
                        });    
                    });
                });
            }
        },
        create:function(){
            //alert("create");
        }
    });
    

    $("#feat_delete_dialog").dialog({
        width: 500,
        height: 150,
        dialogClass: 'generiDefinition_dialog',
        autoOpen: false,
        modal: true,
        close: function () {
        },
        open: function (){
            $del_feat_id = $("#feat_delete_dialog").data("del_feat_id");
            console.log("the deleted feature ID is: "+$del_feat_id);
            
        }
    });

    $("#unit_type_delete_dialog").dialog({
        width: 500,
        height: 150,
        dialogClass: 'generiDefinition_dialog',
        autoOpen: false,
        modal: true,
        close: function () {
        },
        open: function (){
            $del_feat_id = $("#feat_delete_dialog").data("del_feat_id");
            console.log("the deleted feature ID is: "+$del_feat_id);
            
        }
    });


    $("#unit_type_update_dialog").dialog({
        width: 500,
        height: 150,
        dialogClass: 'generiDefinition_dialog',
        autoOpen: false,
        modal: true,
        close: function () {
        },
        open: function (){
            $del_feat_id = $("#feat_delete_dialog").data("del_feat_id");
            console.log("the deleted feature ID is: "+$del_feat_id);
            
        }
    });


    call_prop_def_unit_type_list();

    $("#create_btn_cont").on('click',function(e){
        alert("bb");
        $("#main_container").load("prop_def_unit_type.php #unit_type_edit");
    });

    

    $("#feat_delete_yes_btn_cont").on('click',function(e){
        console.log("feature delete :: YES");
        $del_feat_id = $("#feat_delete_dialog").data("del_feat_id");
        console.log("the deleted feature ID is: "+$del_feat_id);
        delete_feature($del_feat_id);
    });

    $("#feat_delete_no_btn_cont").on('click',function(e){
        console.log("feature delete :: NO");
        $("#feat_delete_dialog").dialog('close');
    });





    $("#unit_type_delete_yes_btn_cont").on('click',function(e){
        console.log("unit type delete :: YES");
        //$("#unit_type_delete_dialog").dialog('close');
        $unit_type_id = $("#unit_type_delete_dialog").data("unit_type_id");
       var selected_attr_val = $("#attrb_dropdown").val();
       var selected_attr_val = $("#attrb_dropdown").val();
       var selected_attr_val = $("#attrb_dropdown").val();
    });var selected_attr_val = $("#attrb_dropdown").val();

    $("#unit_type_delete_no_btn_cont").on('click',function(e){
        console.log("unit type delete :: NO");
        $("#unit_type_delete_dialog").dialog('close');
    });



    $("#unit_type_update_yes_btn_cont").on('click',function(e){
        console.log("unit type update :: YES");
        //$("#unit_type_update_dialog").dialog('close');
        $unit_type_id = $("#unit_type_update_dialog").data("unit_type_id");
        console.log("the unit type ID is: "+$unit_type_id);
        $("#unit_type_update_dialog").dialog('close');
        unit_type_update($unit_type_id);
    });

    $("#unit_type_update_no_btn_cont").on('click',function(e){
        console.log("unit type update :: NO");
        $("#unit_type_update_dialog").dialog('close');
    });

    $(".attrb_dropdown_style").change(function(){
        alert("the attribute dropdown has changed!");
    });
    
    $(".add_attrb_btn_cont_style").on('click',function(e){
        console.log("clicked on add this attribute");
        var selected_attr_val = $("#attrb_dropdown").val();
        var selected_attr_txt = $("#attrb_dropdown option:selected").text();
        console.log("the selected attribute value is"+selected_attr_val);
        //console.log("the selected attribute text is"+selected_attr_txt);
        var feat_id = $("#add_attrib_dialog").data("feat_id");
        console.log("the feature ID is :: "+feat_id);
        insert_in_unit_fea_attrib_tbl(feat_id, selected_attr_val);
        var feat_tbl_object = {};
        feat_tbl_object.op = "a";
        $("#edit_feature_tbl_cont").load("feat_list_tbl.php #main_cont", feat_tbl_object, function(){ // after deleting feature
            $("#feat_delete_dialog").dialog('close');
            $(".delete_feature_style").on('click',function(e){
                console.log("clicked on featur delete");
                console.log("clicked on update unit type name");
                console.log(e);
                console.log(e.target.dataset.ut);
                var del_feat_id = e.target.dataset.ut;
                console.log(del_feat_id);
                $("#feat_delete_dialog").data("del_feat_id",del_feat_id);
                $("#feat_delete_dialog").dialog('open');
                
            });

            $("#add_unit_feature_plus_cont").on('click',function(e){
                console.log("clicked on +");
                $("#add_feat_dialog").dialog('open');   
            });

            $(".add_atr_btn_cont_style").off('click');
            $(".add_atr_btn_cont_style").on('click',function(e){
                console.log("clicked on plus button");
                console.log(e);
                console.log($(this));
                var feat_id = ($(this).data("ut"));
                console.log(feat_id);
                //this.hhjj;
                $(this).data("ut");
                //console.log("here");
                $("#add_attrib_dialog").data("feat_id",feat_id);
                //$("#add_attrib_dialog").data("type","l");
                $("#add_attrib_dialog").dialog('open');
            });

            $(".active_name_style").off('click');
            $(".active_name_style").on('click',function(e){
                console.log("clicked on attribute");
                console.log(e);
                console.log($(this));
                var attrib_id = ($(this).data("att"));
                console.log("attrib_id from attribute table:: "+attrib_id);
                var fea_attrib_id = ($(this).data("f_att"));
                console.log("unit_fea_attrib:: "+fea_attrib_id);
                $("#add_attrib_dialog").data("type","f");
                $("#add_attrib_dialog").data("fea_attrib_id",fea_attrib_id);
                $("#add_attrib_dialog").data("attrib_id",attrib_id);
                $("#add_attrib_dialog").dialog('open');
            });

            $( ".attrib_accordion_style" ).accordion({
                collapsible: true,
                active: false,
                heightStyle:"content",
              });
        });   

        $("#add_attrib_dialog").dialog('close');

    });

    $("#add_feat_btn_cont").on('click',function(e){
        //alert("clicked on add feature");
        console.log("clicked on add_feat_btn_cont");
        var selected_feature_val = $("#feat_dropdown").val();
        var selected_feature_txt = $("#feat_dropdown option:selected").text();
        console.log(selected_feature_val);
        console.log(selected_feature_txt);
        if(selected_feature_val == 0 && selected_feature_txt == "Select Feature"){

        }else{
            call_add_this_feature(selected_feature_val, selected_feature_txt);
        }
    });   
    
    


});

