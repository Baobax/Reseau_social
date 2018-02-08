$( document ).ready(function(){
	
	$('[id^="C2_"]').on("change paste keyup", function(){
		if( !$(this).val() ) { 
			$('[id^="C2_"]').prop("required", false);
		} else {
			$('[id^="C2_"]').prop("required", true);
		}
	});
});