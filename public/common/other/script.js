
function isEmpty(value){
	return (value.length < 1);
}

function validEmail(v) {
    var r = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
    return (v.match(r) == null) ? false : true;
}

function lengthChecker(value){
	return (value.length < 8);
}

function isExists(elem){
	if ($(elem).length > 0) {
		return true;
	}
	return false;
}

function changeUploadedImage(e) {
	var reader = new FileReader();
    var _URL = window.URL || window.webkitURL;

	reader.onload = function (e) {
		$('#uploaded-image').attr('src', e.target.result);
		$('#uploaded-image').addClass('active').fadeIn(2000);
		$('#upload-content').hide();
	};
	reader.readAsDataURL(e.target.files[0]);
}

function validateImage(input, e){
	var imageName = $(input).val(),
		extension = imageName.substring(imageName.lastIndexOf('.') + 1).toLowerCase();

	if (extension == 'jpg' || extension == 'png' || extension == 'jpeg' || extension == 'gif') {
		changeUploadedImage(e);
	} else {
		$(input).val("");
		alert("Invalid Image file.");
	}
}

function ucFirst(str) {
    str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
    str = str.replace('_', ' ');
    return str;
}

function videoType(input){
    var videoField = $(input).data('video');
    $('.video-field').removeClass('active');
    $('.video-field').find('input').removeAttr('data-required');
    $(videoField).addClass('active');
    $(videoField).find('input').attr('data-required', true);
}


(function ($) {
    "use strict";
    videoType('input:radio[name="type"]:checked');

    $('input:radio[name="type"]').change(function () {
        videoType($(this));
    });

    $(window).bind("load", function () {
        $("#preloader").fadeOut();
    });
	
	

    $('[data-validation]').on('submit', function(e){
        $('.image-upload').removeClass('has-error');
        $('input').removeClass('has-error');
        $('select').removeClass('has-error');
        $('textarea').removeClass('has-error');
        $('.err-msg').remove();
        var hasError = false;



        $($(this).find('[data-required]')).each(function(){

            var $this = $(this);

            if(($this.attr('type') != 'hidden') && ($this.data('required') != false)){

                if($this.data('required') == 'dropdown'){
                    if($(this).prop('selectedIndex') < 1){
                        hasError = true;
                        $this.addClass('has-error');
                        $this.after('<h6 class="err-msg">' + ucFirst($this.attr('name')) + ' is required.' + '</h6>');
                    }
                }else if($this.data('required') == 'image'){

                    if(isEmpty($this.val())){
                        if(isEmpty($this.attr('value'))){
                            hasError = true;
                            var imageUpload = $this.closest('.image-upload');
                            imageUpload.addClass('has-error');
                            imageUpload.after('<h6 class="err-msg">' + ucFirst($this.attr('name')) + ' is required.' + '</h6>');
                        }
                    }

                }else if($this.data('required') == true){

                    if($this.attr('type') == 'file') var val = $this.attr('value');
                    else var val = $this.val();

                    if(isEmpty(val)){
                        hasError = true;
                        $this.addClass('has-error');
                        $this.after('<h6 class="err-msg">' + ucFirst($this.attr('name')) + ' is required.' + '</h6>');
                    }
                }else if($this.data('required') == 'numeric'){
                    if(isEmpty($this.val())){
                        hasError = true;
                        $this.addClass('has-error');
                        $this.after('<h6 class="err-msg">' + ucFirst($this.attr('name')) + ' is required.' + '</h6>');

                    }else if(!$.isNumeric($this.val())){
                        hasError = true;
                        $this.addClass('has-error');
                        $this.after('<h6 class="err-msg">' + ucFirst($this.attr('name')) + ' must be numeric.' + '</h6>');

                    }
                }
            }
        });

        if(hasError) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
	});


	/*$('input').each(function(){
		if((!isEmpty($(this).val())) || ($(this).attr('name') == 'new_pass') || ($(this).attr('name') == 'confirm_pass')) $(this).attr("readonly", true);
	});*/
	
    $('[data-confirm]').on('click', function(e){
        if (!confirm($(this).data('confirm'))) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }else {
			alert('Download button is disable for the demo');
			/*return false;*/
		}
    });
    
	
	if(isExists('#uploaded-image')){
		if(!isEmpty($('#uploaded-image').attr('src'))){
			$('#upload-content').hide();
		}
	}

	$('.image-input').on('change', function (e) {

        $('#uploaded-image').attr('src', '');
        $('#upload-content').show();

        var _URL = window.URL || window.webkitURL,
            file = $(this)[0].files[0],
            img = new Image(),
            targetResolution = $(this).data("traget-resolution");

        if(file){
            var fileType = file["type"],
                fileSize = file["size"] / (1024 *1024),
                validImageTypes = ["image/jpeg", "image/png"];

            if ($.inArray(fileType, validImageTypes) < 0) {
                $(this).val('');
                alert("Invalid FileType");
            }else if(fileSize > maxUploadedFile){
                $(this).val('');
                alert('Uploaded Image : ' + fileSize.toFixed(2) + 'MB (Maximum file size : ' + maxUploadedFile + 'MB)');
            }else{
                img.src = _URL.createObjectURL(file);
                img.onload = function() {
                    var imgwidth = this.width,
                        imgheight = this.height;

                    if(targetResolution) $('input[name=' + targetResolution + ']').attr('value', imgwidth + ':' + imgheight);

                    $('#uploaded-image').attr('src', img.src);
                    $('#uploaded-image').addClass('active').fadeIn(2000);
                    $('#upload-content').hide();
                };
            }
        }
	});

	$(window).bind("load", function() {
		if(isExists('.masonry-grid')){
			$('.masonry-grid').masonry({
				itemSelector: '.masonry-item',
				percentPosition: true,
			});
		}
	});
	
})(jQuery);
