$(document).ready(function(){
	$("#finance-body").on("click", "#divisor > li", function(){
		$(this).parent().find("#active").removeAttr("id");
		$(this).attr("id", "active");

		if($(this).text() === "Loggers"){
			$(this).parent().parent().find("#fin-logger").show();
			$(this).parent().parent().find("#fin-task").hide();
			$(this).parent().parent().find("#fin-events").hide();
			$(this).parent().parent().find("#fin-added").hide();
		} else if($(this).text() === "Tasks"){
			$(this).parent().parent().find("#fin-logger").hide();
			$(this).parent().parent().find("#fin-task").show();
			$(this).parent().parent().find("#fin-events").hide();
			$(this).parent().parent().find("#fin-added").hide();
		} else if($(this).text() === "Events"){
			$(this).parent().parent().find("#fin-logger").hide();
			$(this).parent().parent().find("#fin-task").hide();
			$(this).parent().parent().find("#fin-events").show();
			$(this).parent().parent().find("#fin-added").hide();
		} else if($(this).text() === "Added Costs"){
			$(this).parent().parent().find("#fin-logger").hide();
			$(this).parent().parent().find("#fin-task").hide();
			$(this).parent().parent().find("#fin-events").hide();
			$(this).parent().parent().find("#fin-added").show();
		}
	});

	$("#finance-body").on("click", ".f-project > div > div", function(){
		if($(this).parent().next().is(':visible')){
			$(this).parent().next().hide();
		} else {
			$(this).parent().next().show();
		}
	});

	$("#finance-body").on("click", ".f-project > div > a", function(){
		var pid = $(this).attr("data-id");
		window.location.href = "server/php-to-pdf.php?projectid="+pid;
	});
});