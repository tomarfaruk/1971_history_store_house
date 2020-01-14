<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

$radio_youtube = $radio_vimeo = $radio_uploaded = $radio_youku = $radio_link = "";

if(!empty($admin)){
	$video = new Video();
    $video->admin_id = $admin->id;
    $video->status = 1;
    if(Helper::is_get() && isset($_GET["id"])){
        $video->id = $_GET["id"];
        $video = $video->where(["id" => $video->id])->andwhere(["admin_id" => $admin->id])->one();
	}

    $all_category = new Category();
    $all_category = $all_category->where(["admin_id" => $admin->id])->all();

    if($video->type == 1) $radio_youtube = "checked";
    else if($video->type == 2) $radio_vimeo = "checked";
    else if($video->type == 3) $radio_uploaded = "checked";
    else if($video->type == 4) $radio_youku = "checked";
    else if($video->type == 5) $radio_link = "checked";
    else $radio_youtube = "checked";

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

                <form data-validation="true" action="../private/controllers/video.php" method="post" enctype="multipart/form-data">
                    <div class="item-inner">

                        <div class="item-header">
                            <h5 class="dplay-inl-b">Video</h5>

                            <h5 class="float-r oflow-hidden">
                                <label class="status switch">
                                    <input type="checkbox" name="status" <?php if($video->status == 1) echo "checked"; ?>/>
                                    <span class="slider round">
                                        <b class="active">Active</b>
                                        <b class="inactive">Inactive</b>
                                    </span>
                                </label>
                                <span class="toggle-title"></span>
                            </h5>
                        </div><!--item-header-->

                        <div class="item-content">

							<input type="hidden" name="id" value="<?php echo $video->id; ?>">
							<input type="hidden" name="admin_id" value="<?php echo $video->admin_id; ?>">
							<input type="hidden" name="prev_image" value="<?php echo $video->image_name; ?>"/>

                            <label class="control-label" for="file">Image(<?php echo "Max Image Size : " . MAX_IMAGE_SIZE . "MB. Required Format : png/jpg/jpeg"; ?>)</label>

                            <div class="image-upload">
                                <img src="<?php if(!empty($video->image_name))
                                        echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR. $video->image_name; ?>" alt="" id="uploaded-image"/>
                                <div class="h-100" id="upload-content">
                                    <div class="dplay-tbl">
                                        <div class="dplay-tbl-cell">
                                            <i class="ion-ios-cloud-upload"></i>
                                            <h5><b>Choose Your Image to Upload</b></h5>
                                            <h6 class="mt-10 mb-70">Or Drop Your Image Here</h6>
                                        </div>
                                    </div>
                                </div><!--upload-content-->

                                <input data-required="image" type="file" name="image_name" class="image-input" value="<?php echo $video->image_name; ?>"
                                    data-traget-resolution="image_resolution"/>
                                <input type="hidden" name="image_resolution" value="<?php echo $video->image_resolution; ?>"/>
                            </div><!--image-upload-->

                            <label>Title</label>
                            <input data-required="true" type="text" placeholder="Site Title" name="title" value="<?php echo $video->title; ?>"/>

                            <div class="video-radio-container">
                                <label class="video-radio">
                                    <input data-video="#youtube-input" type="radio" name="type" value="<?php echo YOUTUBE_VIDEO; ?>" <?php echo $radio_youtube; ?>/>
                                    <div class="checkmark">
                                        <span class="dplay-tbl"><span class="dplay-tbl-cell"><i class="ion-social-youtube"></i>Youtube</span></span>
                                    </div>
                                </label>

                                <label class="video-radio">
                                    <input data-video="#vimeo-input" type="radio" name="type" value="<?php echo VIMEO_VIDEO; ?>" <?php echo $radio_vimeo; ?>/>
                                    <div class="checkmark">
                                        <span class="dplay-tbl"><span class="dplay-tbl-cell"><i class="ion-social-vimeo"></i>Vimeo</span></span>
                                    </div>
                                </label>

                                <label class="video-radio">
                                    <input data-video="#upload-video-cont" type="radio" name="type" value="<?php echo UPLOADED_VIDEO; ?>" <?php echo $radio_uploaded; ?>/>
                                    <div class="checkmark">
                                        <span class="dplay-tbl"><span class="dplay-tbl-cell"><i class="ion-android-upload"></i>Upload</span></span>
                                    </div>
                                </label>

                                <label class="video-radio">
                                    <input data-video="#youku-input" type="radio" name="type" value="<?php echo YOUKU_VIDEO; ?>" <?php /*echo $radio_youku; */?>/>
                                    <div class="checkmark">
                                        <span class="dplay-tbl"><span class="dplay-tbl-cell"><i class="icon-youku"></i>Youku</span></span>
                                    </div>
                                </label>

                                <label class="video-radio">
                                    <input data-video="#link-input" type="radio" name="type" value="<?php echo VIDEO_LINK; ?>" <?php echo $radio_link; ?>/>
                                    <div class="checkmark">
                                        <span class="dplay-tbl"><span class="dplay-tbl-cell"><i class="ion-link"></i>Link</span></span>
                                    </div>
                                </label>
                            </div><!--video-radio-container-->

                            <div class="video-field active" id="youtube-input">
                                <label>Youtube Link</label>
                                <input type="text" class="" name="youtube" placeholder="eg. https://www.youtube.com/watch?v=GksehGJccqQ"
                                    value="<?php echo $video->youtube; ?>" />
                            </div><!--video-field-->

                            <div class="video-field" id="vimeo-input">
                                <label>Vimeo Link</label>
                                <input type="text" class="" name="vimeo" placeholder="eg. https://vimeo.com/307309034"
                                       value="<?php echo $video->vimeo; ?>" />
                            </div><!--video-field-->

                            <?php
                            $uploadedVideo = "";
                            if(file_exists(UPLOADED_FOLDER . DIRECTORY_SEPARATOR. $video->uploaded_video)) {
                                $uploadedVideo = $video->uploaded_video;
                            } ?>

                            <div class="video-field" id="upload-video-cont">

                                <input type="hidden" name="uploaded_video" value="<?php echo $uploadedVideo; ?>"/>

                                <label class="control-label" for="file">Video(<?php echo "Max Video Size : " . MAX_VIDEO_SIZE . "MB. Required Format : mp4"; ?>)</label>
                                <input type="file" id="video-upload" class="upload-img video" name="upload_video"
                                       data-target-input="uploaded_video"
                                       data-target-duration="duration"
                                       data-progress="#upload-progress"
                                       data-remove-btn="#delete-video"
                                       data-main-video="#main-video"
                                       data-url="api/video/upload-video.php"
                                       value="<?php echo $uploadedVideo; ?>"/>

                                <div class="upload-progress" id="upload-progress">
                                    <div class="oflow-hidden w-100">
                                        <div class="float-l status mb-10"></div>
                                        <div class="float-r">
                                            <a id="delete-video" data-btn-type="cancel" class="link" href="api/video/remove-video.php">Cancel</a>
                                        </div>
                                    </div>
                                    <div class="progress"></div>
                                </div><!--upload-progress-->

                                <div class="main-video" id="main-video">
                                    <video class="mt-20" width="100%" controls><source type="video/mp4"></video>
                                    <div class="vid-content mt-15"><h6 class="size"></h6></div>
                                </div><!--main-video-->

                            </div><!--video-field-->


                            <div class="video-field" id="youku-input">
                                <label>Youku Link</label>
                                <input type="text" class="" name="youku" placeholder="eg. https://v.youku.com/v_show/id_XNDA0ODU1ODMyMA==.html"
                                       value="<?php echo $video->youku; ?>" />
                            </div><!--video-field-->


                            <div class="video-field" id="link-input">
                                <label>Video Link</label>
                                <input type="text" class="" name="video_link" placeholder="eg. https://doamin.com/video.mp4"
                                       value="<?php echo $video->video_link; ?>" />
                            </div><!--video-field-->



                            <h5 class="mtb-30 oflow-hidden">
                                <label href="#" class="switch">
                                    <input type="checkbox" name="featured"
                                        <?php if($video->featured == 1) echo "checked"; ?>/>
                                    <span class="slider round"></span>
                                </label>
                                <span class="toggle-title ml-20">Featured</span>
                            </h5>

                            <label>Category Name</label>
                            <select data-required="dropdown" name="category">

                                <option>Please select a category</option>
                                <?php foreach ($all_category as $cat){ ?>
                                    <?php if($video->category == $cat->id){ ?>
                                        <option value="<?php echo $cat->id; ?>" selected><?php echo $cat->title; ?></option>
                                    <?php } else ?>
                                        <option value="<?php echo $cat->id; ?>"><?php echo $cat->title; ?></option>
                                <?php } ?>
                            </select>

                            <label>Description</label>
                            <textarea data-required="true" class="desc" type="text" name="description" placeholder="Enter Video Description"><?php echo $video->description; ?></textarea>

                            <label>Duration (In Sec)</label>
                            <input data-required="numeric" type="text" name="duration" placeholder="Enter Video Duration in Second" value="<?php echo $video->duration; ?>">

                            <div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Save</b></button></div>
                            
                            <?php if($errors) echo $errors->format(); ?>

                        </div><!--item-content-->
                    </div><!--item-inner-->
                </form>
			</div><!--item-->
		</div><!--item-wrapper-->
	</div><!--main-content-->
</div><!--main-container-->

<?php echo "<script>maxUploadedFile = '" . MAX_IMAGE_SIZE  . "'</script>"; ?>

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
