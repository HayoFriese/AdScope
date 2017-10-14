$(document).ready(function(){		
	var userid = $("#user-id").val();
	var timerstate;

	var min;
	var sec;
	var hr;

	var secShow = $("#log-seconds").text();
	var minShow = $("#log-minutes").text();
	var hrShow = $("#log-hours").text();

	function count_up(){
		if(sec === 59){
			if(min === 59){
				hr++;
				min = 0;
				sec = 0;
			} else {
				min++;
				sec = 0;
			}
		}else{
			sec++;
		}

		if(sec < 10){
			$("#log-seconds").text("0"+sec);
		} else {
			$("#log-seconds").text(sec);
		}

		if(min < 10){
			minShow = $("#log-minutes").text("0"+min);
		} else {
			minShow = $("#log-minutes").text(min);
		}

		if(hr < 10 || hr.length != 2){
			hrShow = $("#log-hours").text("0"+hr);
		} else {
			hrShow = $("#log-hours").text(hr);
		}
	}

	if($("#timer-status").val() === "temp"){
		timerstate = false;
		min = 0;
		sec = 0;
		hr = 0;
	} else if($("#timer-status").val() === "paused"){
		timerstate = false;
		hr = parseInt($("#log-hours").text());
		min = parseInt($("#log-minutes").text());
		sec = parseInt($("#log-seconds").text());
		$("#pause").children().text("Resume");
		$("#pause").attr("id", "resume");

	} else {
		timerstate = true;
		hr = parseInt($("#log-hours").text());
		min = parseInt($("#log-minutes").text());
		sec = parseInt($("#log-seconds").text());
		interval = setInterval(count_up, 1000);
	}

	$("#logger-body > form > article").on("change", "#project", function(){
		var id = $(this).val();
		var taskArr = [];
		$("#task").empty();
        $("#task").append("<option value=\"\">-- Choose--</option>");
		
		if(id != "empty" || id != "none" || id != "other"){	
			$.ajax({
				type: "GET",
				url: "server/get-tasks.php",
				data: {id: id},
				dataType: 'json',
				success: function(data){
					for (var n = 0; n < data.length; n++){
                        var taskid = data[n].id;
                        var taskname = data[n].task;

                        var tasklist = "<option value=\""+taskid+"\">"+taskname+"</option>";
                        taskArr.push(tasklist);
                    }
                    $("#task").append(taskArr);
				}
			});
		}

		$("#task").append("<option value=\"new\">Other Task</option>");
	});

//Start Timer
	$("#logger-body > form > div").on("click", "#start", function(){
		if($("#project").val() != "" && $("#task").val() != ""){
			$(this).children().text("Pause");
			$(this).attr("id", "pause");

			//values
			var timerid = $("#logger-id").val();
			var projectid = $("#project").val();
			var taskid = $("#task").val();
			//Turn status to busy
			$.ajax({
				type: "POST",
				url: "server/change-status.php",
				data: {id: userid,
					status: "Busy"},
				success: function(data){
					$("#headstat").attr("style", "background-color:#A80F0F;");
				}
			});
			timerstate = true;
			interval = setInterval(count_up, 1000);

			//start timer process
			$.ajax({
				type: "POST",
				url: "server/tracker-process.php",
				data: {process: "start-timer",
					timerid: timerid,
					projectid: projectid,
					taskid: taskid},
				success: function(response){
					$("#logger-body > form > article > p > span").text(response);
				}
			});
		}
	});

//Pause
	$("#logger-body > form > div").on("click", "#pause", function(){
		clearInterval(interval);
		var timerid = $("#logger-id").val();
		timerstate = false;
		$(this).children().text("Resume");
		$(this).attr("id", "resume");
		//Turn status to online
		$.ajax({
			type: "POST",
			url: "server/change-status.php",
			data: {id: userid,
				status: "Online"},
			success: function(data){
				$("#headstat").attr("style", "background-color:#88D54F;");

			}
		});
		//set pause timer
		$.ajax({
			type: "POST",
			url: "server/tracker-process.php",
			data: {process: "pause-timer",
				timerid: timerid},
			success: function(response){
				$("#logger-body > form > article > p > span").text(response);
			}
		});
	});

//Resume
	$("#logger-body > form > div").on("click", "#resume", function(){
		$(this).children().text("Pause");
		$(this).attr("id", "pause");

		//values
		var timerid = $("#logger-id").val();
		//Turn status to busy
		$.ajax({
			type: "POST",
			url: "server/change-status.php",
			data: {id: userid,
				status: "Busy"},
			success: function(data){
				$("#headstat").attr("style", "background-color:#A80F0F;");
			}
		});

		$.ajax({
			type: "POST",
			url: "server/tracker-process.php",
			data: {process: "resume-timer",
				timerid: timerid},
			success: function(response){
				$("#logger-body > form > article > p > span").text(response);
			}
		});
		timerstate = true;
		interval = setInterval(count_up, 1000);
	});

//Stop
	$("#logger-body > form > div").on("click", "#stop", function(){
		if(timerstate === true){
			var enddate = new Date($.now());
			clearInterval(interval);
			timerstate = false;
	
			sec = 0;
			min = 0;
			hr = 0;
	
			$("#log-seconds").text("00");
			$("#log-minutes").text("00");
			$("#log-hours").text("00");
			
			var projectid = $("#project").val();
			var timerid = $("#logger-id").val();
			//Turn status to online
			$.ajax({
				type: "POST",
				url: "server/change-status.php",
				data: {id: userid,
					status: "Online"},
				success: function(data){
					$("#headstat").attr("style", "background-color:#88D54F;");
	
				}
			});
			$.ajax({
				type: "POST",
				url: "server/tracker-process.php",
				data: {process: "date-timer",
					timerid: timerid},
				success: function(response){
					$("#pause").children().text("Start");
					$("#pause").attr("id", "start");
	
					$("#logger-body > form > article > p").empty();
					var endspan = "Ended: <span>"+response+"</span>";
					$("#logger-body > form > article > p").append(endspan);

					var title = prompt("Enter a name for the timer:");

					$.ajax({
						type: "POST",
						url: "server/tracker-process.php",
						data: {process: "stop-timer",
							timerid: timerid,
							title: title,
							userid: userid,
							projectid: projectid},
						success: function(response){
							alert("Timer has been saved");
						}
					});
				}
			});
		}
	});

//Past
	$("#logger-body > form > p").on("click", "a", function(){
		$.ajax({
			type: "GET",
			url: "server/past-loggers.php",
			data: {userid: userid},
			success: function(response){
				$("#back-end-body").append(response);
			}
		});
	});
	$("#back-end-body").on("click", "#close-create", function(){
		$("#view-logger").remove();
	});

//New Logger Form
	$("#logger-body > form > div").on("click", "#new", function(){
		var timerid = $("#logger-id").val();
		if(confirm("Are you sure? Any unsaved changes will be discarded.")){
			$.ajax({
				type: "POST",
				url: "server/tracker-process.php",
				data:{process: "new-timer",
				userid: userid,
				timerid: timerid},
				success: function(response){
					location.reload();
				}
			})	
		}
	});
});