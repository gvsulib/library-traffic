jQuery(document).ready(function(){
	jQuery('input, select').not([type="submit"]).not('.whiteboard').addClass('required');
	jQuery("form").validate();
    jQuery("#11").swap(jQuery("#10")); //UX dept wants these switched
});