$(document).ready(function() {
	$("#project-new").on("change", function(){
		if($(this).val() == "project"){
			var id = $("#header-username").attr("data-id");
			$.ajax({
				type: "GET",
				url: "server/new-project.php",
				data: {id: id},
				success: function(success){
					$("#back-end-body").append(success);
				}
			});

		} else if($(this).val() == "client"){

			$.ajax({
				type: "GET",
				url: "server/new-client.php",
				success: function(success){
					$("#back-end-body").append(success);
				}
			});
		}
	});
	$("#projects").on("click", "#tablebody > ul", function(){
		var id = $(this).attr("data-project-id");
		
		if(id === "undefined" || id === undefined || typeof id === undefined){
			alert("This project does not seem to exist!");
		} else{
			window.location.href = "server/project-base.php?id="+id;
		}
	});
	$("#back-end-body").on("click", "#close-create", function(){
		$("#add-new").remove();
		$("#project-new").val("");
	});

});	