$(document).ready(function(){
	$("#avatar").on("click", function(){
		$("#avatar-toggle").trigger('click');
	});
	$("#avatar-toggle").on("change", function(){
		readURL(this);
	});
	$("#close-new").on("click", function(){
		$("#address-new").remove();
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
});