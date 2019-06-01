$(document).ready(function(){
	$("#box_left").on('click',function(e){
		$("#network_main_page").hide();
		//$("#network_list_page").show();
		console.log("getting inside prop_def");
	});
	
	$("#box_center").on('click',function(e){
		$("#network_main_page").hide();
	});

	$("#box_right").on('click',function(e){
		$("#network_main_page").hide();
	});
	
	$("#mytab2").on('click',function(e){
		
	});
});

$(document).ready(function(){
	$("#mytab1").on('click',function(e){
		$(".sub_tab_wrapper_style").hide();
	});

	$("#mytab3").on('click',function(e){
		$(".sub_tab_wrapper_style").hide();
		$("#topic_list_page").show();
	});

	$("#mytab4").on('click',function(e){
		$(".sub_tab_wrapper_style").hide();
	});

	$("#mytab5").on('click',function(e){
		$(".sub_tab_wrapper_style").hide();
	});

	$("#ud_tabs_1").on('click',function(e){
		alert("bb");
	});

	$( function() {
    $( "#test_tab" ).tabs();
  } );

	
});

function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}