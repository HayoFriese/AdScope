<?php
	echo "<section id=\"add-new\">
		<article>
			<form method=\"post\" action=\"server/create-new-client.php\">
				<a id=\"close-create\" href=\"#\"><img src=\"resources/img/icon/cancel.svg\"></a>
				<h1>Create New Client</h1>
				<div>
					<label for=\"client-name\">Client Name</label>
					<input type=\"text\" id=\"client-name\" name=\"client-name\" required>			
				</div>
				<div>
					<label for=\"website\">Main Website</label>
					<input type=\"text\" id=\"website\" name=\"website\" required>
				</div>
				<div>
					<div>
						<label for=\"color\">Color</label>
						<input type=\"text\" maxlength=\"6\" size=\"6\" id=\"color\" name=\"color\" required>
						
					</div>
					<div>
						<label for=\"acronym\">2-Letter Acronym</label>
						<input type=\"text\" maxlength=\"2\" id=\"acronym\" name=\"acronym\" required>
					</div>
				</div>
				<div id=\"form-checklist\">
					<h2>Permissions</h2>
					<label><input type=\"checkbox\" name=\"view\" value=\"1\">Only selected people can view this client and their contacts</label>
					<label><input type=\"checkbox\" name=\"add\" value=\"1\">Only selected people can add contacts to this client</label>
					<label><input type=\"checkbox\" name=\"attach\" value=\"1\">Only selected people can attach clients to projects</label>
				</div>

				<input type=\"submit\" name=\"new-client\" id=\"new-client\" value=\"Create Client\">
			</form>
		</article>
			<script type=\"text/javascript\" src=\"server/js/spectrum.js\"></script>
			<script>
				$(\"#color\").spectrum({
					color: \"#FFFFFF\",
    				showInput: true,
    				className: \"full-spectrum\",
    				preferredFormat: \"hex\",
    				move: function (color) {     
   					
   					},
   					show: function () {
   					
   					},
   					beforeShow: function () {
   					
   					},
   					hide: function () {
   					
   					},
   					change: function() {
   					    
   					}
				});
			</script>
	</section>

	";
?>