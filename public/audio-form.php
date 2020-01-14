<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());



if(!empty($admin)){
	$audio = new Audio();
    
    if(Helper::is_get() && isset($_GET["id"])){
        $audio->id = $_GET["id"];
        
	}

    $all_category = new audio_category();
    $all_category = $all_category->all();


}else Helper::redirect_to("login.php");

?>

<?php require("common/php/php-head.php"); ?>

<body>

<?php require("common/php/header.php"); ?>

<div class="main-container">
	<?php require("common/php/sidebar.php"); ?>

	<div class="main-content">
		<div class="item-wrapper one">

			<div class="item">
                <?php if($message) echo $message->format(); ?>

                <form data-validation="true" action="../private/controllers/audio.php" method="post" enctype="multipart/form-data">
                    <div class="item-inner">

                        <div class="item-header">
                            <h5 class="dplay-inl-b">Audio</h5>
                        </div><!--item-header-->

                        <div class="item-content">

							<input type="hidden" name="id" value="<?php echo $audio->id; ?>">
							<input type="hidden" name="admin_id" value="<?php echo $audio->admin_id; ?>">
							<input type="hidden" name="prev_image" value="<?php echo $audio->audio_img; ?>"/>

                            <!-- <label class="control-label" for="file">Image(<?php echo "Max Image Size : " . MAX_IMAGE_SIZE . "MB. Required Format : png/jpg/jpeg"; ?>)</label> -->
                            

                            <!-- <div class="image-upload">
                                <img src="<?php if(!empty($audio->audio_img))
                                        echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR. $audio->audio_img; ?>" alt="" id="uploaded-image"
                                />
                                <div class="h-100" id="upload-content">
                                    <div class="dplay-tbl">
                                        <div class="dplay-tbl-cell">
                                            <i class="ion-ios-cloud-upload"></i>
                                            <h5><b>Choose Your Image to Upload</b></h5>
                                            <h6 class="mt-10 mb-70">Or Drop Your Image Here</h6>
                                        </div>
                                    </div>
                                </div>upload-content

                                <input data-required="true" type="file" name="image_name" class="image-input" value="<?php echo $audio->audio_img; ?>"/>
                                
                            </div>image-upload -->

                            <label>Audio Thumbnail Link</label>
                            <input type="text" class="" name="image_name" placeholder="eg. https://www.yourimagelink/im1.png" value="" data-required="true">
                            
                            <label>Audio Link</label>
                            <input type="text" class="" name="audio_url" placeholder="eg. https://www.youraudiolink/audio1.mp3" value="" data-required="true">

                            <label>Title</label>
                            <input data-required="true" type="text" placeholder="Site Title" name="title" value="<?php echo $audio->audio_title; ?>"/>

                          <!-- <label>Audio mp3</label>
                        <input data-required="" type="file" name="audio_url" class="audio-input" value="<?php 
                        echo $audio->audio_url; ?>"/> -->


                        <label>Category Name</label>
                        <select data-required="dropdown" name="category">

                            <option>Please select a category</option>
                            <?php foreach ($all_category as $cat){ ?>
                                <option value="<?php echo $cat->cat_id; ?>" selected><?php echo $cat->cat_name; ?></option>
                            <?php } ?>
                        </select>

                            <label>Description</label>
                            <textarea data-required="true" class="desc" type="text" name="description" placeholder="Enter Video Description"><?php echo $audio->audio_description; ?></textarea>
                            
                            <label>Audio Duration</label>
                            <input data-required="numeric" type="text" name="audio_duration" placeholder="Enter Video Duration in Second" value="">

                            <div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Save</b></button></div>
                            
                            <?php if($errors) echo $errors->format(); ?>

                        </div><!--item-content-->
                    </div><!--item-inner-->
                </form>
			</div><!--item-->
		</div><!--item-wrapper-->
	</div><!--main-content-->
</div><!--main-container-->

<?php echo "<script>maxUploadedFile = '" . 25  . "'</script>"; ?>

<?php require("common/php/php-footer.php"); ?>

<script>
    var uploadedFolder = '<?php echo UPLOADED_FOLDER; ?>';
    uploadedFolder = uploadedFolder + '/';

    var uploadVideoAjaxCall,
        refreshDisable = false,
        submitDisable = false;

    function uploadVideo(videoInput){
        var form_data = new FormData(),
            $this = $(videoInput),
            file_data = $this.prop('files')[0],
            formName = $this.attr('name'),
            max_video_size = '<?php echo MAX_VIDEO_SIZE ?>';

        if(file_data != null){
            if(file_data.type == 'video/mp4'){

                if(file_data.size / (1024 * 1024) > max_video_size){
                    alert("Too Large file (Max file size : " + max_video_size + "MB)");
                }else{
                    form_data.append(formName, file_data);
                    saveVideo(videoInput, form_data);
                }
            }else alert("Invalid File(Accepted File : mp4)");
        }
    }

    function saveVideo(videoInput, form_data, url){
        var videoInput = $(videoInput),
            url = videoInput.data('url'),
            targetInput = videoInput.data('target-input'),
            targetDuration = videoInput.data('target-duration'),
            progress = videoInput.data('progress');

        var deleteVideo = $('#delete-video');
        $(deleteVideo).addClass('active');
        $(progress).addClass('active');
        $(progress).find('.progress').css('width', 0 +  '%');

        $(videoInput).prop('disabled', true).addClass('disabled');

        refreshDisable = true;
        submitDisable = true;

        uploadVideoAjaxCall = $.ajax({
            url: url,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'POST',
            success: function (res) {

                var uploadedVideoObj = JSON.parse(JSON.stringify(res));
                if(uploadedVideoObj.status_code == 200){

                    submitDisable = false;
                    refreshDisable = false;

                    $(deleteVideo).attr('data-videon-name', uploadedVideoObj.data.file_name);
                    $('input[name=' + targetInput + ']').attr('value', uploadedVideoObj.data.file_name);
                    $(videoInput).attr('value', uploadedVideoObj.data.file_name);

                    var videoElement = document.createElement('video');
                    videoElement.src = uploadedFolder + uploadedVideoObj.data.file_name;

                    var timer = setInterval(function() {
                        if (videoElement.readyState === 4) {
                            $('input[name=' + targetDuration + ']').attr('value', videoElement.duration);
                            clearInterval(timer);
                        }
                    }, 50);

                }else{
                    $(progress).find('.progress').css('width', 0 +  '%');
                    $(progress).find('.status').text('Upload Failed');
                    $(progress).find('.status').append(uploadedVideoObj.message);
                    $(deleteVideo).removeClass('active');
                }
                loadVideo(videoInput);
            },

            xhr: function(){
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {

                            percent = Math.ceil(position / total * 100);
                            $(progress).find('.progress').css('width', percent +  '%');
                            $(progress).find('.status').text('Uploading... ' + percent + ' %');
                        }
                    }, true);
                }
                return xhr;
            },
            mimeType:"multipart/form-data"
        });
        return uploadVideoAjaxCall;
    }

   function removeVideo(removeBtn){
       var videoName = $(removeBtn).attr('data-video-name'),
           url = $(removeBtn).attr('href');

       var values = {
           'video': videoName,
           'admin_id': '<?php echo $admin->id; ?>'
       };

       var a = $.ajax({
           url: url,
           dataType: 'json',
           cache: false,
           data: values,
           type: 'POST',
           success: function(res) {

               var uploadedVideoObj = JSON.parse(JSON.stringify(res));
               if(uploadedVideoObj.status_code == 200){
                   refreshDisable = false;
                   var videoInput = $('#video-upload'),
                       targetInput = videoInput.data('target-input');

                   $('input[name=' + targetInput + ']').attr('value', '');
                   $(videoInput).attr('value', '');

               }else{
                   $(progress).find('.progress').css('width', 0 +  '%');
                   $(progress).find('.status').text('Upload Failed');
                   $(progress).find('.status').append(uploadedVideoObj.message);
                   $('#delete-video').removeClass('active');
               }
               loadVideo(videoInput);
           }
       });
   }

   function loadVideo(videoInput){
       var videoInput = $(videoInput),
           targetInput = videoInput.data('target-input'),
           mainVideoTitle = $('input[name=' + targetInput + ']').attr('value'),
           progress = videoInput.data('progress'),
           mainVideo= videoInput.data('main-video'),
           removeBtn = videoInput.data('remove-btn');

       $(progress).find('.progress').css('width', 0 +  '%');

       if(mainVideoTitle){
           $(videoInput).prop('disabled', true).addClass('disabled');

           $(mainVideo).addClass('active');
           $(mainVideo).find('video').attr('src', uploadedFolder + mainVideoTitle);

           $(progress).addClass('active');
           $(removeBtn).addClass('active');

           $(progress).find('.status').text("Video Uploaded");
           $(progress).find('#delete-video').text("Remove").attr('data-video-name', mainVideoTitle).attr('data-btn-type', 'remove');
       }else{

           $(mainVideo).removeClass('active');
           $(progress).find('.status').text('Video Deleted');
           $(videoInput).prop('disabled', false).removeClass('disabled').prop('value', '');
           $(removeBtn).removeClass('active').text("Cancel").attr('data-video-name', '').attr('data-btn-type', 'cancel');
       }
   }

    /*MAIN SCRIPTS*/
    (function ($) {
        "use strict";

        loadVideo('#video-upload');

        window.onbeforeunload = function() {
            if(refreshDisable){
                return "Are you sure you want to leave?";
            }
        }

        $("#video-upload").closest('form').on('submit', function(){
            if(submitDisable) {
                alert("Upload is in Progress");
                return false;
            }
        });

        $("#video-upload").change(function (){
            uploadVideo($(this));
        });

        $('#delete-video').on('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            if(confirm("Are You Sure?")){
                var $this = $(this);
                submitDisable = false;

                if($this.data('btn-type') == 'cancel'){
                    uploadVideoAjaxCall.abort();
                    refreshDisable = false;
                    var videoInput = $('#video-upload'),
                        targetInput = videoInput.data('target-input');

                    $('input[name=' + targetInput + ']').attr('value', '');
                    loadVideo(videoInput);
                }else if($this.data('btn-type') == 'remove'){
                    removeVideo($this)
                }
            }
            return false;
        });

    })(jQuery);
</script>
