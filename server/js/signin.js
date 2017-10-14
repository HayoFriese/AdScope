$(document).ready(function() {
	$("#username").on("change", function(){
		var username = $("#username").val();

		$.ajax({
			type: 'POST', 
			url: "server/get-profilepic.php",
			data: {username: username}, 
			success: function(done){
				if(done != "none"){
					$('#sign-in-cont > div > section > article > div > div').css("background-image", "url("+done+")");
				} else {
					$('#sign-in-cont > div > section > article > div > div').css("background-image", "url('resources/img/signin.svg'resources'");
				}
			}
		});
	});
});