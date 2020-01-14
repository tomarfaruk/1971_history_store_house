<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

$radio_youtube = $radio_m3u8 = $radio_rtmp = "";

if(!empty($admin)){
	$livetv = new LiveTV();
    $livetv->admin_id = $admin->id;
    $livetv->status = 1;
    if(Helper::is_get() && isset($_GET["id"])){
        $livetv->id = $_GET["id"];
        $livetv = $livetv->where(["id" => $livetv->id])->andwhere(["admin_id" => $admin->id])->one();

	}
}else Helper::redirect_to("login.php");

if($livetv->type == 1) $radio_youtube = "checked";
else if($livetv->type == 2) $radio_m3u8 = "checked";
else if($livetv->type == 3) $radio_rtmp = "checked";
else $radio_youtube = "checked";


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

                <form data-validation="true" action="../private/controllers/livetv.php" method="post" enctype="multipart/form-data">
                    <div class="item-inner">

                        <div class="item-header">
                            <h5 class="dplay-inl-b">LiveTV</h5>

                            <h5 class="float-r oflow-hidden">
                                <label class="status switch">
                                    <input type="checkbox" name="status"
                                        <?php if($livetv->status == 1) echo "checked"; ?>/>
                                    <span class="slider round">
                                        <b class="active">Active</b>
                                        <b class="inactive">Inactive</b>
                                    </span>
                                </label>
                                <span class="toggle-title"></span>
                            </h5>
                        </div><!--item-header-->


                        <div class="item-content">

							<input type="hidden" name="id" value="<?php echo $livetv->id; ?>">
							<input type="hidden" name="admin_id" value="<?php echo $livetv->admin_id; ?>">
							<input type="hidden" name="prev_image" value="<?php echo $livetv->image_name; ?>"/>

                            <label class="control-label" for="file">Image(<?php echo "Max Image Size : " . MAX_IMAGE_SIZE . "MB. Required Format : png/jpg/jpeg"; ?>)</label>

                            <div class="image-upload">

                                <img src="<?php if(!empty($livetv->image_name))
                                        echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR . $livetv->image_name; ?>"
                                    alt="" id="uploaded-image"/>
                                <div class="h-100" id="upload-content">
                                    <div class="dplay-tbl">
                                        <div class="dplay-tbl-cell">
                                            <i class="ion-ios-cloud-upload"></i>
                                            <h5><b>Choose Your Image to Upload</b></h5>
                                            <h6 class="mt-10 mb-70">Or Drop Your Image Here</h6>
                                        </div>
                                    </div>
                                </div><!--upload-content-->
                                <input data-required="image" type="file" name="image_name" class="image-input"
                                       data-traget-resolution="image_resolution" value="<?php echo $livetv->image_name; ?>"/>
                                <input type="hidden" name="image_resolution" value="<?php echo $livetv->image_resolution; ?>"/>
                            </div>

                            <label>Title</label>
                            <input type="text" data-required="true" placeholder="Site Title" name="title"
                                   value="<?php echo $livetv->title; ?>"/>

                            <div class="video-radio-container">
                                <label class="video-radio">

                                    <input data-video="#youtube-input" type="radio" name="type" value="1" <?php echo $radio_youtube; ?>/>
                                    <div class="checkmark">
                                        <span class="dplay-tbl"><span class="dplay-tbl-cell"><i class="ion-social-youtube"></i>Youtube</span></span>
                                    </div>
                                </label>

                                <label class="video-radio">
                                    <input data-video="#m3u8-input" type="radio" name="type" value="2" <?php echo $radio_m3u8; ?>/>
                                    <div class="checkmark">
                                        <span class="dplay-tbl"><span class="dplay-tbl-cell"><i class="ion-android-laptop"></i>m3u8</span></span>
                                    </div>
                                </label>

                                <label class="video-radio">
                                    <input data-video="#rtmp-input" type="radio" name="type" value="3" <?php echo $radio_rtmp; ?>/>
                                    <div class="checkmark">
                                        <span class="dplay-tbl"><span class="dplay-tbl-cell"><i class="ion-android-laptop"></i>RTMP</span></span>
                                    </div>
                                </label>
                            </div><!--video-radio-container-->


                            <div class="video-field active" id="youtube-input">
                                <label>Youtube Live Link</label>
                                <input type="text" data-required="true" placeholder="Youtube Live Link" name="link" value="<?php echo $livetv->link; ?>"/>
                            </div><!--video-field-->

                            <div class="video-field" id="m3u8-input">
                                <label>m3u8 Link</label>
                                <input type="text" class="" name="m3u8" placeholder="m3u8 Link" value="<?php echo $livetv->m3u8; ?>" />
                            </div><!--video-field-->

                            <div class="video-field" id="rtmp-input">
                                <label>RTMP Link</label>
                                <input type="text" class="" name="rtmp" placeholder="rtmp Link" value="<?php echo $livetv->rtmp; ?>" />
                            </div><!--video-field-->

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