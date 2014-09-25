jQuery(document).ready(function(){
	jQuery('input, select').not([type="submit"]).not('.whiteboard').addClass('required');
	jQuery("form").validate();
});