$(document).ready(function() {
	var userid = $("#header-username").attr("data-id");

	var eventData, submitdata;
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay,listWeek'
		},
		navLinks: true, // can click day/week names to navigate views
		selectable: true,
		selectHelper: true,
		businessHours: {
    		// days of week. an array of zero-based day of week integers (0=Sunday)
    		dow: [ 1, 2, 3, 4, 5 ], // Monday - Thursday

    		start: '9:00', // a start time (10am in this example)
    		end: '18:30', // an end time (6pm in this example)
		},
		fixedWeekCount: false,
		select: function(start, end, view) {
			var startSet =  start.format();
			var endSet = end.format();
			console.log("Date: "+startSet+" "+endSet);
			var title;
			$.ajax({
				type: "GET",
				url: "server/new-event.php?start="+startSet+"&end="+endSet+"&userid="+userid,
				success: function(show){
					$("#back-end-body").append(show);
					if (startSet.indexOf('T') > -1){
						$("#form-checklist > label > input[type='checkbox']").attr("disabled", "disabled");
					} else{
						$("#form-checklist > label > input[type='checkbox']").attr("checked", "checked");
					}

					$("#back-end-body").on("submit", "#adding-new-event", function(e){
						e.preventDefault();
						var invitearray = $("#invitearray").val();
						var fd = new FormData(e.target);
						fd.append("type", "new");
						fd.append("id", userid);

						$.ajax({
							type: "POST",
							url: "server/event-process.php",
							data: fd,
							processData: false,  // tell jQuery not to process the data
							contentType: false,
							success: function(response){
								var json = JSON.parse(response);

								var title = json["title"];
								var id = json["id"];
								var color = json["color"];

								eventData = {
									id: id,
			 						title: title,
			 						start: start,
			 						end: end,
			 						color: color
								};

								$('#calendar').fullCalendar('renderEvent', eventData, true); 
								$("#add-new").remove();
							}
						});
					});
				}
			});

			$('#calendar').fullCalendar('unselect');
		},
		eventClick: function(calEvent, jsEvent, view) {

        	var id = calEvent.id;
        	var color = calEvent.color;
        	$.ajax({
				type: "GET",
				url: "server/view-event.php",
				data: {id : id,
					color : color},
				success: function(show){
					$("#back-end-body").append(show);
				}
        	});
    	},
		editable: true,
		eventDrop: function(event, delta, revertFunc) {
			
        	if (confirm("Are you sure about this change?")) {
        	    var id = event.id;
        	    var start = event.start.format();
        	    var end = event.start.format();
        	    var type = "move";
        	    var allday;
        	    if(event.allDay === false){
        	    	allday = 0;
        	    } else{
        	    	allday = 1;
        	    }

        	    $.ajax({
        	    	type: "POST",
        	    	url: "server/event-process.php",
        	    	data: {eventid: id,
        	    		start: start,
        	    		end: end,
        	    		type: type,
        	    		allday: allday},
					success: function(response){
						alert(event.title + " has been moved to " + response + "!");
					}
        	    });
        	} else {
        		revertFunc();	
        	}

    	},
    	eventResize: function(event, delta, revertFunc) {    	    

    	    if (confirm("Are you sure about this change?")) {
        	    var id = event.id;
        	    var start = event.start.format();
        	    var end = event.end.format();
        	    var type = "move";

        	    $.ajax({
        	    	type: "POST",
        	    	url: "server/event-process.php",
        	    	data: {eventid: id,
        	    		start: start,
        	    		end: end,
        	    		type: type},
					success: function(response){
						alert(event.title + " has been changed to " + response + "!");
					}
        	    });
        	} else {
        		revertFunc();	
        	}

    	},
		eventLimit: true, // allow "more" link when too many events
		events: 'server/get-events.php?userid='+userid
	});
	$("#back-end-body").on("click", "#cancel", function() {
		var id = $(this).attr("data-event-id");
		if(confirm("Are you sure you wish to cancel this event? This cannot be undone.")){
			$("#view-event").remove();
			$('#calendar').fullCalendar('removeEvents', id);
			var type = "cancel";
			$.ajax({
				type: "POST",
				url: "server/event-process.php",
				data: {
					id: id,
					type: type
				},
				success: function(response){
					alert("The following event has been cancelled: "+response);
				}
			});
		}
    });
	
	$("#back-end-body").on("click", "#add-new > article > form > #close-create", function(){
		$("#add-new").remove();
	});

	$("#back-end-body").on("click", "#view-event > article > div > #close-create", function(){
		$("#view-event").remove();
	});
	$("#back-end-body").on("click", "#view-event > article > div > #divisor > li", function(){
		var tx = $(this).text();
		$('#divisor > li').removeAttr("id");
		$(this).attr("id", "active");
		
		if(tx == "Invites"){
			$("#event-details").hide();
			$("#event-invites").show();
		} else if(tx == "Details"){
			$("#event-details").show();
			$("#event-invites").hide();
		}
	});
	$("#back-end-body").on("click", "#add-new > article > form > #divisor > li", function(){
		var tx = $(this).text();
		$('#divisor > li').removeAttr("id");
		$(this).attr("id", "active");
		
		if(tx == "Details"){
			$("#event-details").show();
			$("#event-description").hide();
			$("#event-invites").hide();
		} else if(tx == "Additional Info"){
			$("#event-details").hide();
			$("#event-description").show();
			$("#event-invites").hide();
		} else if(tx == "Attendees"){
			$("#event-details").hide();
			$("#event-description").hide();
			$("#event-invites").show();
		}
	});

	$("#back-end-body").on("change", "#start-time", function(){
		var checkboxes = $("#form-checklist > label > input[type='checkbox']");

		if($("#start-time").val().length === 5){
			$("#start-time").attr("required", "required");
			$("#end-time").attr("required", "required");
			
			checkboxes.removeAttr("checked");
			checkboxes.attr("disabled", "disabled");

		} else if($("#end-time").val().length === 5){
			$("#start-time").attr("required", "required");
			$("#end-time").attr("required", "required");

			checkboxes.attr("disabled", "disabled");
			checkboxes.removeAttr("checked");

		} else{
			$("#start-time").removeAttr("required");
			$("#end-time").removeAttr("required");
			if(checkboxes.attr("disabled") === "disabled"){
				checkboxes.removeAttr("disabled");
				checkboxes.prop("checked");
			}
		}
		if($("#start-time").val().length < 5 && $("#end-time").val().length < 5){
			checkboxes.prop("checked", !checkboxes.prop("checked"));
		}

	});

	var invitelist = [userid];

	$("#back-end-body").on("click", "#invite-contact > a", function(){

		if($(this).find("div").attr("style") != "background-image: url(resources/img/icon/checkmark.svg);"){
			var id = $(this).attr("data-id");
			var src = $(this).attr("data-src");
			if(src === "resources/img/icon/avatar.svg"){
				box = '<div id="contact-to-add" data-contact-id="'+id+'"><a href="#" data-src="'+src+'"><img src="resources/img/icon/cancel.svg"></a><div id="client-contact-img" style="background-image: url(resources/img/icon/avatar.svg); background-size:70%;"></div></div>';
			} else {
				box = '<div id="contact-to-add" data-contact-id="'+id+'"><a href="#" data-src="'+src+'"><img src="resources/img/icon/cancel.svg"></a><div id="client-contact-img" style="background-image: url('+src+');"></div></div>';
			}
				
			$("#invite-arr").find("span").remove();
			$("#invite-arr").append(box);
			invitelist.push(id);
			$("#invitearray").val(invitelist);

			$(this).find("div").attr("style", "background-image: url(resources/img/icon/checkmark.svg);");
		}
	});

	$("#back-end-body").on("click", "#contact-to-add > a", function(){
		var id = $(this).parent().attr("data-contact-id");
		var src = $(this).attr("data-src");

		$(this).parent().remove();
		invitelist.splice(invitelist.indexOf(id), 1);
		$("#invitearray").val(invitelist);

		$("#event-invites > div").find("#invite-contact > a[data-id='"+id+"']").children("div").attr("style", "background-image: url("+src+");");
	});
});