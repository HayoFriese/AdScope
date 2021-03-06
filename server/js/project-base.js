$(document).ready(function() {
/*General*/

	$("#base-nav > ul > li > a").on("click", function(){
		var tx = $(this).text();

		if(tx == "Project Details"){
			$('#base-nav > ul > li > a').removeClass('active');
			$(this).addClass("active");

			$("#info").show();
			$("#tasks").hide();
			$("#contacts").hide();
			$("#finances").hide();
			$("#dates").hide();
			$("#files").hide();
			$("#notes").hide();

			$("#contacts").find("#adding-contact").remove();
			$("#contacts").find("#select-contact").remove();
			$("#contacts > #button > select").val("view");
		}
		if(tx == "Tasks"){
			$('#base-nav > ul > li > a').removeClass('active');
			$(this).addClass("active");

			$("#info").hide();
			$("#tasks").show();
			$("#contacts").hide();
			$("#finances").hide();
			$("#dates").hide();
			$("#files").hide();
			$("#notes").hide();

			$("#contacts").find("#adding-contact").remove();
			$("#contacts").find("#select-contact").remove();
			$("#contacts > #button > select").val("view");
		}
		if(tx == "Contacts"){
			$('#base-nav > ul > li > a').removeClass('active');
			$(this).addClass("active");

			$("#info").hide();
			$("#tasks").hide();
			$("#contacts").show();
			$("#finances").hide();
			$("#dates").hide();
			$("#files").hide();
			$("#notes").hide();

			$("#contacts").find("#adding-contact").remove();
			$("#contacts").find("#select-contact").remove();
			$("#contacts > #button > select").val("view");
		}
		if(tx == "Financials"){
			$('#base-nav > ul > li > a').removeClass('active');
			$(this).addClass("active");

			$("#info").hide();
			$("#tasks").hide();
			$("#contacts").hide();
			$("#finances").show();
			$("#dates").hide();
			$("#files").hide();
			$("#notes").hide();

			$("#contacts").find("#adding-contact").remove();
			$("#contacts").find("#select-contact").remove();
			$("#contacts > #button > select").val("view");
		}
		if(tx == "Dates"){
			$('#base-nav > ul > li > a').removeClass('active');
			$(this).addClass("active");

			$("#info").hide();
			$("#tasks").hide();
			$("#contacts").hide();
			$("#finances").hide();
			$("#dates").show();
			$("#files").hide();
			$("#notes").hide();

			$("#contacts").find("#adding-contact").remove();
			$("#contacts").find("#select-contact").remove();
			$("#contacts > #button > select").val("view");
		}
		if(tx == "Files"){
			$('#base-nav > ul > li > a').removeClass('active');
			$(this).addClass("active");

			$("#info").hide();
			$("#tasks").hide();
			$("#contacts").hide();
			$("#finances").hide();
			$("#dates").hide();
			$("#files").show();
			$("#notes").hide();

			$("#contacts").find("#adding-contact").remove();
			$("#contacts").find("#select-contact").remove();
			$("#contacts > #button > select").val("view");
		}
		if(tx == "Notes"){
			$('#base-nav > ul > li > a').removeClass('active');
			$(this).addClass("active");

			$("#info").hide();
			$("#tasks").hide();
			$("#contacts").hide();
			$("#finances").hide();
			$("#dates").hide();
			$("#files").hide();
			$("#notes").show();

			$("#contacts").find("#adding-contact").remove();
			$("#contacts").find("#select-contact").remove();
			$("#contacts > #button > select").val("view");
		}
	});

	$("#float-submit").on("click", function(e){
		e.preventDefault();
		$("#note-submit").val($("#project-notes").html());
		$("#base").submit();
	});


/*Tasks*/
	$("#notify-label > img").on("click", function(){
        if($("#notify-label > img").attr("src") == "../resources/img/icon/bell.svg"){
        	$("#notify-label > img").attr("src", "../resources/img/icon/bell-no.svg");
        } else{
        	$("#notify-label > img").attr("src", "../resources/img/icon/bell.svg");
        }
	});
	$("#priority-label > img").on("click", function(){
        if($("#priority-label > img").attr("src") == "../resources/img/icon/star-filled-black.svg"){
        	$("#priority-label > img").attr("src", "../resources/img/icon/star-faded.svg");
        } else{
        	$("#priority-label > img").attr("src", "../resources/img/icon/star-filled-black.svg");
        }
	});
	$("#recurring-label > img").on("click", function(){
        if($("#recurring-label > img").attr("src") == "../resources/img/icon/two-circling-arrows.svg"){
        	$("#recurring-label > img").attr("src", "../resources/img/icon/two-circling-arrows-no.svg");
        } else{
        	$("#recurring-label > img").attr("src", "../resources/img/icon/two-circling-arrows.svg");
        }
	});


	$("#add-task").on("click", function(){
		var taskname = $("#task").val();

		if(taskname != "" || taskname != undefined || taskname != null){

			var userid = $("#header-username").data("id");
			var projectid = $("#project-id").val();
			var notify, priority, recurring;
	

			if($("input[name='notify']:checked").val()){
				notify = $("#notify").val();
			} else{
				notify = 0;
			}
			if($("input[name='priority']:checked").val()){
				priority = $("#priority").val();
			} else{
				priority = 0;
			}
			
			if($("input[name='recurring']:checked").val()){
				recurring = $("#recurring").val();
			} else{
				recurring = 0;
			}
	
			var type = "new";
	
			$.ajax({
				type: "POST",
				url: "task-process.php",
				data: {type: type,
					userid: userid,
					projectid: projectid,
					taskname: taskname,
					notify: notify,
					priority: priority,
					recurring: recurring},
				success: function(response){
					$("#tasks-div > div > ul").empty();

					$("#tasks-div > div > ul").load("view-tasks.php?id="+projectid);
				}
			});
		}
	});
	
	$("#tasks").on("click", "#done-task", function(){
		var taskid = $(this).attr("data-task-id");
		var userid = $("#header-username").data("id");
		var type = "done";
		var projectid = $("#project-id").val();

		$.ajax({
			type: "POST",
			url: "task-process.php",
			data: {taskid: taskid,
				userid: userid,
				type: type
			},
			success: function(response){
				$("#tasks-div > div > ul").empty();

				$("#tasks-div > div > ul").load("view-tasks.php?id="+projectid);
			}
		});
	});

/*Contacts*/
	var contactlist = [];

	$("#contacts > #button > select").on("change", function(){
		var val = $(this).val();
		var id = $("#project-id").val();
		if(id === ""){
			id = 0;
		}
		if(val === "new"){
			var contact = "client";
			var locationint = 2;

			$.ajax({
        	    type: "GET",
        	    url: "new-contact-2.php",
        	    data: {contact: contact},
        	    success: function(success){
        	    	$("#back-end-body").append(success);
        		}
    		});
		}else if(val === "add"){
			$("#contacts > #contacts-div > #view-contacts").remove();
			var locationint = 2;
			var pid = id;
			var cid = $("#client-id").val();

			$.ajax({
        	    type: "GET",
        	    url: "project-pick-contact.php",
        	    data: {pid: pid,
        	    		cid: cid},
        	    success: function(success){
        	    	$("#contacts > #contacts-div").append(success);
        		}
    		});
		}else if(val === "view"){
			$("#contacts > #contacts-div > #adding-contact").remove();
			$("#contacts > #contacts-div > #select-contact").remove();
			var locationint = 2;



			var id = $("#project-id").val();
			$.ajax({
        	    type: "GET",
        	    url: "project-view-contact.php",
        	    data: {},
        	    success: function(success){
        	    	$("#contacts-div").load("project-view-contact.php?id=" + id);
        		}
    		});
		}
	});

	$("#contacts").on("click", "#toggle-view > a", function(){
		var id = $(this).attr("data-contact-id");
		
		$.ajax({
            type: "POST",
            url: "contact-card-2.php",
            data: {id: id},
            success: function(stat){
            	$("#back-end-body").append(stat);
        	}
    	});
	});

	$("#contacts").on("click", "#toggle-add > a", function(){
		if($(this).text() === "Add Contact"){
			var id = $(this).attr("data-contact-id");
			var src = $(this).attr("data-src");
			if(src === "../resources/img/icon/avatar.svg"){
				box = '<div id="contact-to-add" data-contact-id="'+id+'"><a href="#"><img src="../resources/img/icon/cancel.svg"></a><div id="client-contact-img" style="background-image: url(../resources/img/icon/avatar.svg); background-size:70%;"></div></div>';
			} else {
				box = '<div id="contact-to-add" data-contact-id="'+id+'"><a href="#"><img src="../resources/img/icon/cancel.svg"></a><div id="client-contact-img" style="background-image: url('+src+');"></div></div>';
			}
			$("#adding-contact").append(box);
			contactlist.push(id);
			$("#relatedcontact").val(contactlist);

			$(this).text("Remove Contact");

		} else if($(this).text() === "Remove Contact"){
			var id = $(this).attr("data-contact-id");

			$("#adding-contact").find($("[data-contact-id ="+id+"]")).remove();
			contactlist.splice(contactlist.indexOf(id), 1);
			$("#relatedcontact").val(contactlist);

			$(this).text("Add Contact");
		}
	});

	$("#contacts").on("click", "#contact-to-add > a", function(){
		var id = $(this).parent().attr("data-contact-id");

		$(this).parent().remove();
		contactlist.splice(contactlist.indexOf(id), 1);
		$("#relatedcontact").val(contactlist);


		$("#select-contact #toggle-add").find($("[data-contact-id="+id+"]")).text("Add Contact");
	});

	$("#back-end-body").on("submit", "#address-new > article > form", function(e){
		e.preventDefault();
		var form = e.target;
		var fd = new FormData(form);
		var projectid = $("#project-id").val();

		$.ajax({
			type: 'POST',
			url: "new-contact-submit-2.php",
			data: fd,
			contentType: false,
			processData: false,
			success: function(done){
				var arr = done.split('&%');
				var id = arr[0];
				var contact = arr[1];

				$("#address-new").remove();
				$("#contacts > #button").val("");
				$("#view-contacts").remove();
				$("#adding-contact").remove();
				$("#select-contact").remove();

				$("#button > select").val("view");

				$.ajax({
        	    	type: "POST",
        	    	url: "move-contact-to-client.php",
        	    	data: {clientid: id,
        	    		projectid: projectid,
        	    		contactid: contact},
        	    	contentType: false,
        	    	processData: false,
        	    	success: function(success){
        	    		$("#contacts-div").load("client-view-contact.php?id=" + id);
        				$.ajax({
	        	    		type: "GET",
	        	    		url: "client-view-contact.php",
	        	    		data: {},
	        	    		success: function(success){
	        	   	 			$("#contacts-div").load("client-view-contact.php?id=" + id);
	        				}
	    				});
        			}
    			});
			}
		});
	});

	$("#back-end-body").on("click", "#avatar", function(){
		$("#avatar-toggle").trigger('click');
	});
	$("#back-end-body").on("change", "#avatar-toggle", function(){
		readURL(this);
	});
	function readURL(input){

   		if (input.files && input.files[0]) {
        	var reader = new FileReader();

        	reader.onload = function (e) {
        	    $('#image-preview').css('background-image', 'url(' + e.target.result + ')');
        	    $('#image-preview').css('background-size', 'cover');
        	    $('#image-preview').css('opacity', '1');
        	}

        	reader.readAsDataURL(input.files[0]);
    	}
	}
	$("#back-end-body").on("click", "#close-new", function(){
		$("#address-new").remove();
	});
	$("#contacts").on("click", "#toggle-remove > a", function(){
		var id = $(this).attr("data-contact-id");
		var project = $("#project-id").val();

		if(id != ""){
			if(project != ""){
				$.ajax({
					type: "POST",
        		    url: "project-remove-contact.php",
        		    data: {id: id,
        		    	project: project},
        		    success: function(stat){
        		    	alert(stat);
        		    	$("#view-contacts").remove();
        		    	$("#contacts-div").load("project-view-contact.php?id=" + project);
        			}
				});
			}
		}
	});
	
/*Dates*/
	// $("#add-dates").on("click", function(){
	// 	var datename = $("#add-date").val();

	// 	if(datename != "" || datename != undefined || datename != null){

	// 		var userid = $("#header-username").data("id");
	// 		var projectid = $("#project-id").val();
	// 		var notify, priority, recurring;
	

	// 		if($("input[name='notify']:checked").val()){
	// 			notify = $("#notify").val();
	// 		} else{
	// 			notify = 0;
	// 		}
	// 		if($("input[name='priority']:checked").val()){
	// 			priority = $("#priority").val();
	// 		} else{
	// 			priority = 0;
	// 		}
	
	// 		var type = "new";
	
	// 		var fd = new FormData();
	// 		fd.append("type", "new");
	// 		fd.append("userid", userid);
	// 		fd.append("projectid", projectid);
	// 		fd.append("taskname", taskname);
	// 		fd.append("notify", notify);
	// 		fd.append("priority", priority);
	// 		fd.append("recurring", recurring);
	
	// 		$.ajax({
	// 			type: "POST",
	// 			url: "task-process.php",
	// 			data: {type: type,
	// 				userid: userid,
	// 				projectid: projectid,
	// 				taskname: taskname,
	// 				notify: notify,
	// 				priority: priority,
	// 				recurring: recurring},
	// 			success: function(response){
	// 				$("#tasks-div > div > ul").empty();

	// 				$("#tasks-div > div > ul").load("view-tasks.php?id="+projectid);
	// 			}
	// 		});
	// 	}
	// });

/*Finances*/
	$("#finances").on("click", "#divisor > li", function(){
		$('#divisor > li').removeAttr("id");
		$(this).attr("id", "active");

		if($(this).text() === "Tasks"){
			$("#finance-task").show();
			$("#finance-events").hide();

		} else if($(this).text() === "Events"){
			$("#finance-task").hide();
			$("#finance-events").show();
		}
	});

/*Files*/
	$("#add-file").on("click", function(){
		if(!$("#project-id").val()){
			alert("Please create the Project first. Enter the required fields to start the project.")
		} else{
			var uploader = $("#header-username").data("id");
			var input = $('#project-file')[0].files;
			var projectid = $('#project-id').val();
			var desc = $("#file-description").val();
			var title = $("#file-subject").val();

			var files = [];

			var fd = new FormData();
				fd.append('uploader', uploader);
				fd.append('title', title);
				fd.append('info', desc);
				fd.append('projectid', projectid);
				for (var i = 0; i < input.length; i++) {
					var file = input[i];
					fd.append('projectfiles[]', file);
				}

				$.ajax({
					type: 'POST', 
					url: "project-add-files.php",
					data: fd,
					contentType: false,
    			    processData: false, 
					success: function(done){
						alert(done);
						$("#file-subject").val("");
						$("#file-description").val("");
						$('#project-file').val("");
					}
				});
		}
	});
});