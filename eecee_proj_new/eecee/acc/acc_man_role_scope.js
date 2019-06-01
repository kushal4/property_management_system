
function init_role_tree(node_to_be_loaded){
	console.log("getting inside get_prop_name");
	var object = node_to_be_loaded;

	return $.ajax({
		method: "POST",
		url: "init_role_tree.php",
		//url: "get_prop_topo.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					role_tree = data.role;
					role_root = data.root;
					role_root.children.push(role_tree);
					//result = data.role;
					result = role_root;
					console.log ("get_prop_topo::after AJAX::topo_tree:: result");
					console.log ("********************acc_male_role Tree******************");
					console.log(result);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	  }).then(function() {
			console.log ("inside AJAX Then");
			return $.Deferred(function(def) {
				console.log ("inside AJAX deferred");
				console.log (result);
		  		def.resolveWith({},[result]);
			}).promise();
	  });

}


function init_role_tree_by_perm(node_to_be_loaded){
	console.log("getting inside get_prop_name");
	//alert("steffi");
	var object = node_to_be_loaded;

	return $.ajax({
		method: "POST",
		url: "init_role_tree_by_perm.php",
		//url: "get_prop_topo.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					role_tree = data.role;
					role_root = data.root;
					role_root.children.push(role_tree);
					//result = data.role;
					result = role_root;
					console.log ("get_prop_topo::after AJAX::topo_tree:: result");
					console.log ("********************acc_male_role Tree******************");
					console.log(result);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	  }).then(function() {
			console.log ("inside AJAX Then");
			return $.Deferred(function(def) {
				console.log ("inside AJAX deferred");
				console.log (result);
		  		def.resolveWith({},[result]);
			}).promise();
	  });

	
}

function role_scope_target_role(node_to_be_loaded){
	console.log("getting inside role_scope_target_role");
	var object = node_to_be_loaded;
	console.log(object);
	return $.ajax({
		method: "POST",
		url: "role_scope_target_role.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			//alert(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					role_tree = data.role;
					role_root = data.root;
					role_root.children.push(role_tree);
					//result = data.role;
					result = role_root;
					console.log ("get_prop_topo::after AJAX::topo_tree:: result");
					console.log ("********************acc_male_role Tree******************");
					console.log(result);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	  }).then(function() {
			console.log ("inside AJAX Then");
			return $.Deferred(function(def) {
				console.log ("inside AJAX deferred");
				console.log (result);
		  		def.resolveWith({},[result]);
			}).promise();
	  });

	
}

function role_dets_by_hier(node_to_be_loaded){
	console.log("getting inside get_prop_name");
	var object = node_to_be_loaded;

	return $.ajax({
		method: "POST",
		url: "role_dets_by_hier.php",
		//url: "get_prop_topo.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					role_tree = data.role;
					role_root = data.root;
					role_root.children.push(role_tree);
					//result = data.role;
					result = role_root;
					console.log ("get_prop_topo::after AJAX::topo_tree:: result");
					console.log ("********************acc_male_role Tree******************");
					console.log(result);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	  }).then(function() {
			console.log ("inside AJAX Then");
			return $.Deferred(function(def) {
				console.log ("inside AJAX deferred");
				console.log (result);
		  		def.resolveWith({},[result]);
			}).promise();
	  });

	
}

function refresh_scope_list(role_sig, cbfunc){
	console.log("getting inside refresh_scope_list");
    var object = {};
    object.role_sig = role_sig;
	$.ajax({
		method: "POST",
		url: "refresh_scope_list.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("refresh_scope_list::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if(data.ret_code == 0) { 
					$("#dym_role_scope_tbl").tablecreator("clearTable");
					scope_roles = data.roles_array;
					if( scope_roles == null){
						alert("null");
					}else{
						
						console.log(scope_roles);
						create_role_scope_table(scope_roles);
					}
					
					console.log("type of cbfunc");
					console.log(typeof cbfunc);
					//if(cbfunc != null){ 
					if(typeof cbfunc === 'function'){ 
						cbfunc(role_sig); 
					}; 
				} else {
				
				}
			}
		},
		failure: function () {
            console.log("failure");
		}
	});
}

function refresh_scope_tree(role_sig){
	//var inst = $.jstree.reference("PROP_ROOT_02");
	var inst = $.jstree.reference('#role_scope_role_tree');
	console.log("role_scope_target_role INST");
	console.log(inst);
	var jstree_root_node = inst.get_node("PROP_ROOT_02");
	jstree_root_node.data = {target_role_sig:role_sig};
	console.log ("jstree_root_node");
	console.log(jstree_root_node);
	var get_children_dom = inst.get_children_dom("PROP_ROOT_02");
	console.log(get_children_dom);
	$(get_children_dom).each(function (i, index) {
		var child_id = index.id;
		console.log(child_id);
		var get_child_node = inst.get_node(child_id);
		inst.delete_node(get_child_node); // delete the node
	});
	inst.refresh_node(jstree_root_node);
}


function create_role_scope_table(scope_roles){
    $("#dym_role_scope_tbl").tablecreator("clearTable");
    $(scope_roles).each(function (i, net) {
       
        var role_sig = net.sig;
        var role_name = net.text;
        console.log(role_name);
        
        var tr = $("<tr/>");
        var td1 = $("<td/>");
		var td2 = $("<td/>");
		var button = $("<button/>");
		
		button.html("x");
		button.attr("id", "b-" + role_sig);
		button.attr("class", "cross_button_style");
		button.data("role_sig", role_sig);
        
        var anc = $("<a/>");
        var role_row_id = Math.floor(Math.random() * 100000);
        anc.data("role_sig", role_sig);
        anc.attr("id", "a-" + role_sig);
        anc.html(role_name);
        td1.append(anc);
        td2.append(button);
    
        tr.append(td1);
        tr.append(td2);
        $("#dym_role_scope_tbl").tablecreator("createRow", { sig: "a-" + role_row_id, tablerow: tr });
    });
}


function add_scope_role(scope_role_sig, target_role_sig){
    console.log("getting inside add_scope_role");
    var object = {};
    object.scope_role_sig = scope_role_sig;
    object.target_role_sig = target_role_sig;
	$.ajax({
		method: "POST",
		url: "add_scope_role.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_scope_role::AJAX Return: ");
			console.log(data);
			
			
			if (data.ret_code == 4) {
				//var status = data.session_expire;
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if(data.ret_code == 0) { 
				   console.log("select role ajax success");
					var scope_roles = data.scope_role_array;
				   create_role_scope_table(scope_roles);
				   refresh_scope_list(target_role_sig, refresh_scope_tree);
				} else {
					console.log("Unknown RetCode");
				}
			}
		},
		failure: function () {
			//alert("fail");
			console.log("failure");
		}
	});
}

function delete_scope_role(target_role_sig, scope_role_sig){
	console.log("getting inside delete_scope_role");
	var object = {};
	object.target_role_sig = target_role_sig;
	object.scope_role_sig = scope_role_sig;
	console.log(object);
	$.ajax({
		method: "POST",
		url : "delete_scope_role.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_scope_role::AJAX Return: ");
			console.log(data);
			if (data.ret_code == 4) {
				//var status = data.session_expire;
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if(data.ret_code == 0) { 
				   //console.log("select role ajax success");
		   
				   //create_role_scope_table(scope_roles);
				   refresh_scope_list(target_role_sig, refresh_scope_tree);
				} 
			}
		},
		failure: function () {
			//alert("fail");
			console.log("failure");
		}
	});

}


function set_target_role_id_in_sess(){
	console.log("getting inside set_target_role_id_in_sess");
	var target_role_sig = $("#select_role_accordion").data("target_role_id"); 
    var object = {};
    object.target_role_sig = target_role_sig;
	$.ajax({
		method: "POST",
		url: "set_target_role_id_in_sess.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("set_target_role_id_in_sess::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if(data.ret_code == 0) { 
				   console.log("saved target_role_id_in_sess");
				} else {
				
				}
			}
		},
		failure: function () {
            console.log("failure");
		}
	});
}


$(document).ready(function(){
	
	
	//$(".cross_button_style").on('click', function (e) {
	//	alert("clicked on x");
	//	console.log(this);
//	});

	

   $('#role_scope_role_tree').jstree({
    'plugins': ["state", "contextmenu", "dnd", "types"],
	'core': {
         /*       
        'data' : [
            {
                'id' : 'PROP_ROOT_02',
                'text' : 'Please Select a Property',
                'children' : []
            }
		],*/
        'data' : function (target_node_obj, callback) {
            console.log("JSTREE loading");
            console.log(target_node_obj);
            console.log("target_node_obj.data");
			console.log(target_node_obj.data);
			//var target_role_sig = $("#select_role_accordion").data("target_role_id"); 

			//console.log(target_role_sig);
            var argobj = {};

			if (target_node_obj.data==null){
				argobj.target_role_sig = "0";
			} else {
				argobj.target_role_sig = target_node_obj.data.target_role_sig;
			}
			
			//argobj.target_role_sig = target_role_sig;

			console.log("argobj");
			console.log(argobj);

            role_scope_target_role(argobj).done(function(result){ 
                console.log ("role_scope_role_tree :: JSTREE FEEDER: " + result);
                console.log(this);
                console.log(result);
                callback.call(this, result);	
                });
            }, 
        'check_callback': true
        
        },
    "types": {
        
        "role" : {
            "icon" : "../ext-styles/themes/default/ricon24.png?"+new Date().getTime(),

        },
        "rolecat" : {
            "icon" : "../ext-styles/themes/default/cicon24.png?"+new Date().getTime(),
            
        },
        "role_dcm" : {
            "icon" : "../ext-styles/themes/default/ricon24.png?"+new Date().getTime(),
            "a_attr" : { "style" : "color:#787f94" } ,
            
        },
        "role_link" : {
            "icon" : "../ext-styles/themes/default/rlink24.png?"+new Date().getTime(),
            
        },
        "role_res" : {
            "icon" : "../ext-styles/themes/default/rlocked24.png?"+new Date().getTime(),
        },
        "cat_res" : {
            "icon" : "../ext-styles/themes/default/clocked24.png?"+new Date().getTime(),
        }
    }
});

$('#role_scope_role_tree_acc').jstree({
    'plugins': ["state", "contextmenu", "dnd", "types"],
	'core': {
          /*      
        'data' : [
            {
                'id' : 'PROP_ROOT',
                'text' : 'Please Select a Property',
                'children' : []
            }
		],*/
        'data' : function (target_node_obj, callback) {
            console.log("JSTREE loading");
            console.log(target_node_obj);
            console.log("target_node_obj.data");
			console.log(target_node_obj.data);
			console.log(target_node_obj.userid);
            var argobj = {};
            argobj.userid = target_node_obj.userid;
            init_role_tree_by_perm(argobj).done(function(result){ 
                console.log ("role_scope_role_tree_acc :: JSTREE FEEDER: " + result);
                console.log(this);
                console.log(result);
                callback.call(this, result);	
                });
            },  
        'check_callback': true
        
        },
    "types": {
        
        "role" : {
            "icon" : "../ext-styles/themes/default/ricon24.png?"+new Date().getTime(),

        },
        "rolecat" : {
            "icon" : "../ext-styles/themes/default/cicon24.png?"+new Date().getTime(),
            
        },
        "role_dcm" : {
            "icon" : "../ext-styles/themes/default/ricon24.png?"+new Date().getTime(),
            "a_attr" : { "style" : "color:#787f94" } ,
            
        },
        "role_link" : {
            "icon" : "../ext-styles/themes/default/rlink24.png?"+new Date().getTime(),
            
        },
        "role_res" : {
            "icon" : "../ext-styles/themes/default/rlocked24.png?"+new Date().getTime(),
        },
        "cat_res" : {
            "icon" : "../ext-styles/themes/default/clocked24.png?"+new Date().getTime(),
        }
    }
});

/*
$('#dym_role_scope_role_tree').jstree({
    'plugins': ["state", "contextmenu", "checkbox", "types"],
	'core' : {
		'data' : [
			'Simple root node',
			{
				'id' : 'node_2',
				'text' : 'Root node with options',
				'state' : { 'opened' : true, 'selected' : true },
				'children' : [ { 'text' : 'Child 1' }, 'Child 2']
			}
		]
	}
});
*/

$('#select_role_accordion').on('show.bs.collapse', function () {
    //on clicking the accordion menu
    //alert("expand");
   });

   /*
   $( "#select_role_accordion" ).on( "accordionactivate", function( event, ui ) {
    alert("expand 2");
   } );
   */

   $( "#select_role_accordion" ).on( "accordionactivate", function( event, ui ) {
    //alert("active");
    var role_name = $("#select_role_accordion").data("target_role_name");
    //console.log(role_name);
    var x = document.getElementById("select_role_accordion").firstChild;
    //console.log(x);
    if(role_name != undefined){
        $(x).html("Selected Role: "+role_name);
    }
    
   } );

   $('#role_scope_role_tree_acc').on("select_node.jstree", function (e, data) { 
		console.log("selected node");
		console.log(data);
		var type = data.node.type;
		var role_name = data.node.text;
		var role_id = data.node.id;
		if(type == "role"){
				$("#select_role_accordion").data("target_role_name", role_name);
				$("#select_role_accordion").data("target_role_id", role_id);

				//set_target_role_id_in_sess();
				$('#select_role_accordion').accordion({
					active: false,
					collapsible: true            
				});
				//$('#select_role_accordion').accordion().open_tab(1);
				$('#select_role_accordion').accordion("open_tab", 1);
				console.log ("Opened");
		
				
				if (role_id==0) {
					tree_prop_name = "Please Select a Role Scope";
				}
				refresh_scope_list(role_id, refresh_scope_tree);
		}
	
		
	});


$('#role_scope_role_tree').on("select_node.jstree", function (e, data) { 
   console.log("selected node");
   console.log(data);
   var type = data.node.type;
   var role_name = data.node.text;
   var role_id = data.node.id;
   if(type == "role" || type == "role_res"){
        $("#something").data("scope_role_name", role_name);
        $("#something").data("scope_role_id", role_id);
        document.getElementById("mid_button").disabled = false;
   }else{
	document.getElementById("mid_button").disabled = true;
   }
   
   /*
   $('#select_role_accordion').accordion({
        active: false,
        collapsible: true            
    });*/

});

$("#mid_button").on('click', function (e) {
    console.log("clicked on => button");
    //$("#something").data("role_name", role_name);
    var scope_role_sig =  $("#something").data("scope_role_id");
    var target_role_sig = $("#select_role_accordion").data("target_role_id"); 
    console.log(scope_role_sig);
	console.log(target_role_sig);
	
	//var inst = $.jstree.reference("PROP_ROOT_02");
	//var jstree_root_node = inst.get_node("PROP_ROOT_02");
	//jstree_root_node.data = {target_role_sig:target_role_sig};
	//var x=inst.refresh_node(jstree_root_node);

	//add_scope_role(scope_role_sig, target_role_sig,function(){refresh_scope_list(target_role_sig, function(){inst.refresh_node(target_role_sig)});});
	add_scope_role(scope_role_sig, target_role_sig);
});



$("#dym_role_scope_tbl").tablecreator({
    headingStyle: 'generic-table-heading client-table-heading',/**style classes appends to header element */
    headingTextStyle: '',/**style classes appends to header element span */
    headingText: '',
    contentStyle: 'table_wrapper client_list_table_wrapper',/**style classes appends to content div */
    contentTableStyle: 'generic_table client_table_cls',/**style classes appends to content table */
    tableCols: [{ text: 'Role', class: 'role_scp_col1', colsig: 'role_id' }, { text: '', class: 'role_scp_col2', colsig: 'cross' }],
    tableHeaderStyle: 'tableHeaderStyle',
});

$('body').on('click', '.cross_button_style', function(e) {
	//alert("clicked on x");
	console.log(this.id);
	console.log(e);
	var node_id = this.id;
	var res = node_id.split("-");
	var res_01 = res[0];
	var res_02 = res[1];
	console.log("the first part is: "+res_01);
	console.log("the second part is: "+res_02);

	/*
	var inst = $.jstree.reference('#role_scope_role_tree_acc');
	var jstree_root_node = inst.get_node("PROP_ROOT_02");
	var target_node = inst.select_node(jstree_root_node);
	//var target_node = inst.select_node("#role_scope_role_tree_acc");
	console.log(target_node);
	*/
	var target_role_sig = $("#select_role_accordion").data("target_role_id"); 
	console.log("the target role sig:: "+target_role_sig);

	delete_scope_role(target_role_sig, res_02);

});

refresh_scope_list(0);





});