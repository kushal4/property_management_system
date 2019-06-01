(function(){

    function get_issue_cat(){
        console.log("getting inside get_prop_name");
        var object = {};
        var topo_tree;
        
        return $.ajax({
            method: "POST",
            url: "manage_issue_categories_api.php",/*"get_prop_topo.php"*/
            data: { method: "list" },
            dataType: "json",
            success: function (data, textStatus, jQxhr) {
                console.log("add_new_topics::AJAX Return: ");
				console.log(data);
				var processed_nodes=[];
				
				if(data.status==0){
					var node={
						id:"root",
						text:"Property",
						parent:"#",
					};
					processed_nodes.push(node);
					$.each(data.nodes,function(k,v){
						var node={
							id:"",
							text:"",
							parent:"root",
						};
						console.warn(v);
						if(v.parent==0){
							node.id="n_"+v.id;
							node.text=v.name;
							node.parent="root"
						}
						else{
							node.id="n_"+v.id;
							node.text=v.name;
							node.parent="n_"+v.parent;
						}
                        processed_nodes.push(node);
					});
					result = processed_nodes;
				}
				
				
            },
            failure: function () {
				alert("fail");
            }
          }).then(function() {
                console.log ("inside AJAX Then");
                return $.Deferred(function(def) {
                    console.log ("inside AJAX deferred");
                      def.resolveWith({},[result]);
                }).promise();
          });
    
        
	}
	function addCustomMenuItem(itm, key, obj){
		itm[key] = obj;
	}
	function create(nodeid){
		var parent="#";
		if(nodeid!="root"){
			//alert(nodeid);
           parent=nodeid;
		}


        $.ajax({
            method: "POST",
            url: "manage_issue_categories_api.php",/*"get_prop_topo.php"*/
            data: { method: "create",text:$("#cat_txt").val(),parent:parent },
            dataType: "json",
            success: function (data, textStatus, jQxhr) {
                console.log("add_new_topics::AJAX Return: ");
				console.log(data);
				var processed_nodes=[];
				
				if(data.status==0){
					$("#manage_issue_cat_div").dialog('close');
					$('#cat_tree').jstree(true).refresh();
				}
				
				
            },
            failure: function () {
				alert("fail");
            }
          });
	}
	function update(nodeid){
		var parent="#";
		if(nodeid!="root"){
			//alert(nodeid);
           parent=nodeid;
		}


        $.ajax({
            method: "POST",
            url: "manage_issue_categories_api.php",/*"get_prop_topo.php"*/
            data: { method: "update",text:$("#cat_txt").val(),s:parent },
            dataType: "json",
            success: function (data, textStatus, jQxhr) {
                console.log("add_new_topics::AJAX Return: ");
				console.log(data);
				var processed_nodes=[];
				
				if(data.status==0){
					$("#manage_issue_cat_div").dialog('close');
					$('#cat_tree').jstree(true).refresh();
				}
				
				
            },
            failure: function () {
				alert("fail");
            }
          });
	}
	function del(nodeid){
		var parent="#";
		if(nodeid!="root"){
			//alert(nodeid);
           parent=nodeid;
		}


        $.ajax({
            method: "POST",
            url: "manage_issue_categories_api.php",/*"get_prop_topo.php"*/
            data: { method: "delete",s:parent },
            dataType: "json",
            success: function (data, textStatus, jQxhr) {
                console.log("add_new_topics::AJAX Return: ");
				console.log(data);
				var processed_nodes=[];
				
				if(data.status==0){
					$("#manage_issue_cat_div").dialog('close');
					$('#cat_tree').jstree(true).refresh();
				}
				
				
            },
            failure: function () {
				alert("fail");
            }
          });
	}
	function customMenu(node) {
		// The default set of all items
		 target_node_id = node.id;
		isRoot = ($("#"+target_node_id).hasClass("topo_root"));
		
		var user_det_flag = node.original.ud;
		var isGrp=false;
		
		if (node.type != null) {
			isGrp = (node.type=="group");
		}
	
		console.log("isGrp");
		console.log(isGrp);
		 console.log(node);
		if (target_node_id=="root") {
			var items = {};
			
				obj = 	{ // The "rename" menu item
						"label": "Create Category",
						"action"			: function (data) {
							//console.log("refresh");
							var inst = $.jstree.reference(data.reference);
							obj = inst.get_node(data.reference);
							//console.log(obj.id);
							var node_id = obj.id;
							$("#create_mul_prop").data("node_id",node_id);//parent id of multiple prop
							$("#manage_issue_cat_div").dialog('open');
							$("#add_button").text("Add Category");
							$("#add_button").off('click');
							$("#add_button").on('click',function(){
                                     create(node_id);
							});
							//alert("create multiple");
							//inst.refresh();
						}
				};
				addCustomMenuItem(items, "create" , obj);
			
			
			
		} else {
			var items = {};
			
			obj1 = 	{ // The "rename" menu item
						"label": "Create Category",
						"action"			: function (data) {
							//console.log("refresh");
							var inst = $.jstree.reference(data.reference);
							obj = inst.get_node(data.reference);
							console.log(obj.id);
							var node_id = obj.id;
							$("#create_mul_prop").data("node_id",node_id);//parent id of multiple prop
							$("#manage_issue_cat_div").dialog('open');
							$("#add_button").text("Add Category");
							$("#add_button").off('click');
							$("#add_button").on('click',function(){
                                         create(node_id);
							});
						}
				};
				addCustomMenuItem(items, "create" , obj1);
				obj2 = 	{ // The "rename" menu item
						"label": "Update Category",
						"action"			: function (data) {
							//console.log("refresh");
							var inst = $.jstree.reference(data.reference);
							obj = inst.get_node(data.reference);
							//console.log(obj.id);
							var node_id = obj.id;
							//$("#create_mul_prop").data("node_id",node_id);//parent id of multiple prop
							//$("#create_mul_prop").dialog('open');
							//alert("create multiple");
							//inst.refresh();
							$("#add_button").text("update Category");
							$("#add_button").off('click');
							$("#add_button").on('click',function(){
								update(node_id);
							});
							$("#manage_issue_cat_div").dialog('open');
						}
				};
				addCustomMenuItem(items, "update" , obj2);
				obj3 = 	{ // The "rename" menu item
						"label": "Delete Category",
						"action": function (data) {
							//console.log("refresh");
							var inst = $.jstree.reference(data.reference);
							obj = inst.get_node(data.reference);
							//console.log(obj.id);
							var node_id = obj.id;
							//$("#create_mul_prop").data("node_id",node_id);//parent id of multiple prop
							//$("#create_mul_prop").dialog('open');
							del(node_id);;
							//alert("create multiple");
							//inst.refresh();
						}
				};
				addCustomMenuItem(items, "delete" , obj3);
				
				obj4 = 	{ // The "rename" menu item
						"label": "Cut",
						"action": function (data) {
							//console.log("refresh");
							var inst = $.jstree.reference(data.reference);
							obj = inst.get_node(data.reference);
							//console.log(obj.id);
							var node_id = obj.id;
							tran.which="cut";
							tran.src=node_id;
							
						}
				};
				addCustomMenuItem(items, "cut" , obj4);
				obj5 = 	{ // The "rename" menu item
						"label": "Copy",
						"action": function (data) {
							//console.log("refresh");
							var inst = $.jstree.reference(data.reference);
							obj = inst.get_node(data.reference);
							//console.log(obj.id);
							var node_id = obj.id;
							tran.which="copy";
							tran.src=node_id;
							
						}
				};
				addCustomMenuItem(items, "copy" , obj5);
				if(tran.which!=null){
					obj6 = 	{ // The "rename" menu item
					"label": "Paste",
					"action": function (data) {
						//console.log("refresh");
						var inst = $.jstree.reference(data.reference);
						obj = inst.get_node(data.reference);
						//console.log(obj.id);
						var node_id = obj.id;
						tran.dst=node_id;
						transfer();
					}
			};
			addCustomMenuItem(items, "paste" , obj6);
			}
				
				
				
			
		}
		return items;
	}
	var tran={
		which:null,
		src:null,
		dst:null

	}
	function transfer(){
//alert(tran.which);
		var data={
			method: "paste",what:null,src:null,dest:null
		}
		if(tran.which=="cut"){
			data.what="cut";

		}
		else if(tran.which=="copy"){
			data.what="copy";
		}
		else{
			return;
		}
		data.src=tran.src;
		data.dest=tran.dst;
		$.ajax({
            method: "POST",
            url: "manage_issue_categories_api.php",/*"get_prop_topo.php"*/
            data: data,
            dataType: "json",
            success: function (data, textStatus, jQxhr) {
                console.log("add_new_topics::AJAX Return: ");
				console.log(data);
				var processed_nodes=[];
				tran.which=null;
				tran.src=null;
				tran.dst=null;
				if(data.status==0){
					//$("#manage_issue_cat_div").dialog('close');
					$('#cat_tree').jstree(true).refresh();
				}
				
				
            },
            failure: function () {
				alert("fail");
            }
          });

	}

		$('#cat_tree').on('ready.jstree', function (node, parent, position) {
			console.log ("jstree.ready event fired");
			var inst = $.jstree.reference("#cat_tree"),
			//obj = inst.get_node(data.reference);
			//get_children_dom
			obj = inst.get_node(".jstree-node");
			root_node = $(".jstree-node");
			//console.log(inst);
			console.log(obj);
			cdom = inst.get_children_dom(root_node);
			console.log("cdom");
			console.log(cdom);
			
		}).jstree({
			"deselect_all": true,
			'plugins': ["state", "contextmenu",  "types"],
			'contextmenu': {items: customMenu},
			'core': {
					
				

					'data' : function (obj, callback) {
						console.log("JSTREE loading");
						//console.log(this);
						//callback.call(this, ['Root 1', 'Root 2']);
						get_issue_cat().done(function(result){ 
							console.log ("JSTREE FEEDER: " + result);
							console.log(result);
							callback.call(this, result);	
						});
					},
					

					'check_callback': true
					},
			"types": {
				
				"unit" : {
					"icon" : "../ext-styles/themes/default/unit.ico?"+new Date().getTime(),

					"type": "unit"

				},
				"group" : {
					"icon" : "../ext-styles/themes/default/group.ico?"+new Date().getTime(),
					"type": "group"
				}

			}		
		}).on('create_node.jstree', function (node, parent, position) {
			console.log ("jstree.create_node event fired");
			console.log(parent);
			console.log(parent.node);
			var parent_id = parent.parent;
			console.log("the parent ID is::"+parent_id);
			//console.log(parent.node);
			var node_id = parent.node.id;
			var node_name = parent.node.text;
			console.log("the node ID is::"+node_id);
			console.log("the node name is::"+node_name);
			//prop_topo_create_node(parent_id, node_id, node_name);
			prop_topo_create_node($('#prop_topo_tree'), parent.node);
		});
	})();

	
	
	


	
	

	


    