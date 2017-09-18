$(function(){

	var $uploadWrapper = $('#upload_wrapper');
	if(!$uploadWrapper.length){
		return false;
	}

	const threshold = 0.93;
	const app = new Clarifai.App({
		apiKey: 'ea746f008b50445aa47ffb018f3d5681',
	});


	$('#albumchoice').on('change', function(e){
		document.location = $('body').data('path') + '/aupload/' + $(this).val();
	});

	var last = null;
	$('#upload_wrapper').on('focus', '.tags-text', function(e){
		last = $(this).val();
	});

	$('#upload_wrapper').on('blur', '.tags-text', function(e){
		var val = $(this).val();
		if(val != last){
			$(this).save();
		}
	});

	$('#upload_wrapper').on('click', '.save-tags', function(e){
		$(this).save();
	});

	$('#upload_wrapper').on('click', '.auto-tags', function(e){
		$(this).autoTag();
	});

	$.fn.autoTag = function(){
		var $self = $(this);
		var $imagecell = $self.parents('.imagecell:first');
		var image = $imagecell.data('name');
		var $tagField = $imagecell.find('.tags-text:first');
		var tags = $tagField.val();
		tags = tags ? tags.split(',') : [];
		var path = $('body').data('path')+"/img/large/"+image;
		//var path = "http://austinweidler.com/images/Handcraft1%20(render%20open).jpg";
		
		app.models.predict(Clarifai.GENERAL_MODEL, path).then(
			function(response) {
				for(var i=0; i<response.outputs.length; i++){
					var concepts = response.outputs[i].data.concepts;

					for(var j=0; j<concepts.length; j++){
						var tag = concepts[j].name;
						var value = concepts[j].value;

						if(value > threshold && tag.indexOf(',') == -1 && $.inArray(tag.trim(), tags) == -1){
							tags.push(tag);
						}
					}
				}

				var tagstring = tags.join(',');
				$tagField.val(tagstring);
				$self.save();
			},
			function(err) {
				console.log(err);
			}
		);
	}

	$.fn.save = function(){
		var $imagecell = $(this).parents('.imagecell:first');
		var tags = $imagecell.find('.tags-text:first').val();
		var file = $imagecell.data('file');
		var token = $imagecell.data('token');

		$.post($('body').data('path') + '/aupload/fsave/'+parseInt(file), {
			file: file,
			tags: tags,
			_token: token,
		}, function(result){
			console.log(result);
		});
	}
});