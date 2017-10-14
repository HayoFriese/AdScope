$(document).ready(function() {
/*General*/
	$('#color').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});

	$("#base-nav > ul > li > a").on("click", function(){
		var tx = $(this).text();

		if(tx == "Client Information"){
			$('#base-nav > ul > li > a').removeClass('active');
			$(this).addClass("active");

			$("#info").show();
			$("#contacts").hide();
			$("#finances").hide();
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
			$("#contacts").show();
			$("#finances").hide();
			$("#files").hide();
			$("#notes").hide();

			$("#contacts").find("#adding-contact").remove();
			$("#contacts").find("#select-contact").remove();
			$("#contacts > #button > select").val("view");
		}
		if(tx == "Financial Details"){
			$('#base-nav > ul > li > a').removeClass('active');
			$(this).addClass("active");

			$("#info").hide();
			$("#contacts").hide();
			$("#finances").show();
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
			$("#contacts").hide();
			$("#finances").hide();
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
			$("#contacts").hide();
			$("#finances").hide();
			$("#files").hide();
			$("#notes").show();

			$("#contacts").find("#adding-contact").remove();
			$("#contacts").find("#select-contact").remove();
			$("#contacts > #button > select").val("view");
		}
	});

	$("#float-submit").on("click", function(e){
		e.preventDefault();
		$("#note-submit").val($("#client-notes").html());
		$("#base").submit();
	});


/*Contacts*/
	var contactlist = [];

	$("#contacts > #button > select").on("change", function(){
		var val = $(this).val();
		var id = $("#client-id").val();
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

			$.ajax({
        	    type: "GET",
        	    url: "client-pick-contact.php",
        	    data: {id: id},
        	    success: function(success){
        	    	$("#contacts > #contacts-div").append(success);
        		}
    		});
		}else if(val === "view"){
			$("#contacts > #contacts-div > #adding-contact").remove();
			$("#contacts > #contacts-div > #select-contact").remove();
			var locationint = 2;



			var id = $("#client-id").val();
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
		var client = $("#client-id").val();

		if(id != ""){
			if(client != ""){
				$.ajax({
					type: "POST",
        		    url: "client-remove-contact.php",
        		    data: {id: id,
        		    	client: client},
        		    success: function(stat){
        		    	$("#view-contacts").remove();
        		    	$("#contacts-div").load("client-view-contact.php?id=" + client);
        			}
				});
			}
		}
	});
	
/*Finances*/
	$(".client-finances").on("click", "#divisor > li", function(){
		$('#divisor > li').removeAttr("id");
		$(this).attr("id", "active");

		if($(this).text() === "Projects"){
			$("#finance-projects").show();
			$("#finance-tasks").hide();
			$("#finance-events").hide();
		} else if($(this).text() === "Tasks"){
			$("#finance-projects").hide();
			$("#finance-tasks").show();
			$("#finance-events").hide();
		} else if($(this).text() === "Events"){
			$("#finance-projects").hide();
			$("#finance-tasks").hide();
			$("#finance-events").show();
		}
	});


/*Files*/
	$("#add-file").on("click", function(){
		if(!$("#client-id").val()){
			alert("Please create the client first. Enter the name and website, and select a color to be able to create the client.")
		} else{
			var uploader = $("#header-username").data("id");
			var input = $('#client-file')[0].files;
			var clientid = $('#client-id').val();
			var desc = $("#file-description").val();
			var title = $("#file-subject").val();

			var files = [];

			var fd = new FormData();
				fd.append('uploader', uploader);
				fd.append('title', title);
				fd.append('info', desc);
				fd.append('clientid', clientid);
				for (var i = 0; i < input.length; i++) {
					var file = input[i];
					fd.append('clientfiles[]', file);
				}

				$.ajax({
					type: 'POST', 
					url: "client-add-files.php",
					data: fd,
					contentType: false,
    			    processData: false, 
					success: function(done){
						alert(done);
						$("#file-subject").val("");
						$("#file-description").val("");
						$('#client-file').val("");
					}
				});
		}
	});
});
