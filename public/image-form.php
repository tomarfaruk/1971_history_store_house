<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
	$image = new Image();
    $image->admin_id = $admin->id;
    if(Helper::is_get() && isset($_GET["id"])){
		$image->id = $_GET["id"];
		$image = $image->where(["id" => $image->id])->andwhere(["admin_id" => $admin->id])->one();
    }
    $all_category = new Img_category();
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
                <form data-validation="true" action="../private/controllers/image.php" method="post" enctype="multipart/form-data">

                    <div class="item-inner">

                        <div class="item-header">
                            <h5 class="dplay-inl-b">Image</h5>

                        </div><!--item-header-->

                        <div class="item-content">
							<input type="hidden" name="id" value="<?php echo $image->id; ?>">
							<input type="hidden" name="admin_id" value="<?php echo $image->admin_id; ?>">

                            <!-- <label class="control-label" for="file">Image(<?php echo "Max Image Size : " . MAX_IMAGE_SIZE . "MB. Required Format : png/jpg/jpeg"; ?>)</label>
                            <div class="image-upload"> -->

                                <!-- <img src="<?php if(!empty($category->image_name))
                                    echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR . $category->image_name; ?>" alt="" id="uploaded-image"/>
                                <div class="h-100" id="upload-content">
                                    <div class="dplay-tbl">
                                        <div class="dplay-tbl-cell">
                                            <i class="ion-ios-cloud-upload"></i>
                                            <h5><b>Choose Your Image to Upload</b></h5>
                                            <h6 class="mt-10 mb-70">Or Drop Your Image Here</h6>
                                        </div>
                                    </div> 
                                </div>-->
                                <!--upload-content-->
                                
                                <!-- <input data-required="image" type="file" name="image_name" class="image-input" data-traget-resolution="image_resolution"
                                       value="<?php echo $image->img_url; ?>"/> -->
                               
                            

                            <label>Document Thumbnail Link</label>
                            <input type="text" class="" name="image_name" placeholder="eg. https://www.yourdocumenthumbaillink/im1.png" value="" data-required="true">


                            <label>Title</label>
                            <input data-required="true" type="text" placeholder="Site Title" name="title" value="<?php echo $image->img_title; ?>"/>

                            <label>Category Name</label>
                            <select data-required="dropdown" name="category">

                                <option>Please select a category</option>
                                <?php foreach ($all_category as $cat){ ?>
                                    <option value="<?php echo $cat->img_cat_id; ?>" selected><?php echo $cat->cat_name; ?></option>
                                <?php } ?>
                            </select>

                            <label>Description</label>
                            <textarea data-required="true" class="desc" type="text" name="description" placeholder="Enter Video Description"><?php echo $image->img_desc; ?></textarea>


                            

                            <div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Save</b></button></div>
                            </div>
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