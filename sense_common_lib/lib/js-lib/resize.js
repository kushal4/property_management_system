$(document).ready(function(){
	setWidth();
	window.resize=setWidth;
	$(window).resize(function(){
		setWidth();
	});
	function setWidth(){
		
		var w = window.outerWidth;
		console.log(w);
		var remainnning_space=w-980;
		var each_size=remainnning_space/2;
		console.error(each_size);
		$('body,html').width(w);
		
		$("#header-cont-left").width(each_size);
		$("#header-cont-right").width(each_size);
		$("#header-cont-center").css({"margin": "0 "+each_size+"px"});
		$("#header-cont-center").width(980);
		$("#center-wrap").width(980);
		
		
		$(".holygrail .colmid").css({"margin-left":-each_size});
		 
		$(".holygrail .colleft").css({"left":remainnning_space});
		$(".holygrail .col1wrap").css({"right":each_size});
		
	     var margin=each_size;
		$(".holygrail .col1").css({"margin":"0 "+margin+"px"});
		var col2_width=each_size-(2*15);
		$(".holygrail .col2").width(col2_width);
		$(".holygrail .col3").width(col2_width);
		
	}
	
	
})