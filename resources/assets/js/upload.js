$(function(){
	$('#albumchoice').on('change', function(e){
		document.location = $('body').data('path') + '/aupload/' + $(this).val();
	});
});