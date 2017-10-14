$(document).ready(function(){
	$("#new-contact-select").on("change", function(){
		if($(this).val() == "client"){
			var contact = "client";

			$.ajax({
        	    type: "GET",
        	    url: "server/new-contact.php",
        	    data: {contact: contact},
        	    success: function(success){
        	    	$("#back-end-body").append(success);
        		}
    		});

		} else if($(this).val() == "other"){

			var contact = "other";

			$.ajax({
        	    type: "GET",
        	    url: "server/new-contact.php",
        	    data: {contact: contact},
        	    success: function(success){
        	    	$("#back-end-body").append(success);
        		}
    		});
		}
	});

	$("#toggle-view > a").on("click", function(){
		var id = $(this).attr("data-contact-id");
		
		$.ajax({
            type: "POST",
            url: "server/contact-card.php",
            data: {id: id},
            success: function(stat){
            	$("#back-end-body").append(stat);
        	}
    	});
	});
});