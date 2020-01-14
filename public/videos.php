<?php require_once('../private/init.php'); ?>

<?php
$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
	$all_videos = new Video();
	$pagination = "";
	$pagination_msg = "";

	if(Helper::is_get()){
		$page = Helper::get_val('page');
		if($page){
			$pagination = new Pagination($all_videos->count(), BACKEND_PAGINATION, $page, "videos.php");
			if(($page > $pagination->get_page_count()) || ($page < 1)) $pagination_msg = "Nothing Found.";
		}else {
			$page = 1;
			$pagination = new Pagination($all_videos->count(), BACKEND_PAGINATION, $page, "videos.php");
		}
	}

	$start = ($page - 1) * BACKEND_PAGINATION;
	$all_videos = $all_videos->where(["admin_id" => $admin->id])->orderBy("id")->desc()->limit($start, BACKEND_PAGINATION)->all();

	$all_category = new Category();
	$all_category = $all_category->where(["admin_id" => $admin->id])->all();
	$car_arr = [];
	foreach ($all_category as $categry){
		$car_arr[$categry->id] = $categry->title;
	}
}else Helper::redirect_to("login.php");

?>


<?php require("common/php/php-head.php"); ?>

	<body>

<?php require("common/php/header.php"); ?>

	<div class="main-container">

		<?php require("common/php/sidebar.php"); ?>

		<div class="main-content">
			<?php if($message) echo $message->format(); ?>

			<div class="oflow-hidden mb-15 mb-xs-0">
				<h4 class="float-l mb-15 lh-45 lh-xs-40">Videos</h4>
				<h6 class="float-r mb-15"><b><a class="c-btn" href="video-form.php">+ Add Video</a></b></h6>
			</div>

			<?php if(!empty($pagination_msg)) echo $pagination_msg; ?>

			<div class="item-wrapper">
				<div class="masonry-grid four">

					<?php if(count($all_videos) > 0){
						foreach ($all_videos as $video){ ?>
							<div class="masonry-item">
								<div class="item">

									<div class="item-inner">

										<div class="video-header">
											<h6 class="all-status video-status"><?php
												if($video->status == 1) echo "<span class='active'>Active</span>";
												else echo "<span class='inactive'>Inactive</span>"; ?>
											</h6>

											<?php if($video->featured == 1) { ?>
												<h6 class="featured">Featured</h6>
											<?php }?>
										</div>

                                        <div class=" plr-25">
											<p class="mt-15 mb-15 pr-60"><?php echo $video->title; ?></p>
											<?php if($video->type == 1) {

												$youtube_video = explode("watch?v=", $video->youtube);
												$youtube_video = "https://www.youtube.com/embed/" . $youtube_video[count($youtube_video) -1]; ?>
												<iframe width="100%" src="<?php echo $youtube_video; ?>" allowfullscreen></iframe>

											<?php } else if($video->type == 2){

												$vimeo_video = explode("/", $video->vimeo);
												$vimeo_video = "https://player.vimeo.com/video/" . $vimeo_video[count($vimeo_video) -1]; ?>
												<iframe src="<?php echo $vimeo_video; ?>" width="100%" height="240" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>

											<?php } else if($video->type == 3) { ?>

												<video height="300" width="100%" controls>
													<source src="<?php echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR . $video->uploaded_video; ?>" type="video/mp4">
												</video>

											<?php } else if($video->type == 4) {

												$youku_video = explode("https://v.youku.com/v_show/id_", $video->youku);
												$youku_video = explode(".html", $youku_video[count($youku_video) - 1]);
												$youku_video = "https://player.youku.com/embed/" . $youku_video[0]; ?>

												<iframe width="100%" height="300" src="<?php echo $youku_video; ?>" allowfullscreen></iframe>

											<?php } else if($video->type == 5) { ?>

												<video height="300" width="100%" controls>
													<source src="<?php echo $video->video_link; ?>">
												</video>

											<?php } ?>

											<div class="oflow-hidden mt-15 mb-20">
												<div class="float-l circle-img-50 mr-10 brdr-primary-1">
													<img src="<?php echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR . $video->image_name; ?>" alt="">
												</div>
												<div class="float-l">
													<h6 class="color-semi-dark mb-5">Category</h6>
													<h5><?php echo $car_arr[$video->category]; ?></h5>
												</div>

												<div class="float-r right-text">

													<?php
														if($video->type == 1) $video_type = "Youtube";
														else if($video->type == 2) $video_type = "Vimeo";
														else if($video->type == 3) $video_type = "mp4";
														else if($video->type == 4) $video_type = "Youku";
														else if($video->type == 5) $video_type = "Video link";
														else $video_type = "Unknown";
													?>

													<h6><a href="#" class="type-btn"><b><?php echo $video_type; ?></b></a></h6>
													<h6 class="mt-10">
														<span class="ml-10"><i class="ion-eye mr-5 color-semi-dark"></i><?php echo $video->view_count; ?></span>
													</h6>
												</div><!--float-r-->
											</div><!--pos-relative-->
										</div><!--p-25-->

										<div class="item-footer two">
                                            <a href="<?php echo 'video-form.php?id=' . $video->id; ?>"><i class="ion-compose"></i></a>
                                            <a href="<?php echo '../private/controllers/video.php?id=' . $video->id . '&&admin_id=' . $video->admin_id?>"
                                               data-confirm="Are you sure you want to delete this item?"><i class="ion-trash-a"></i></a>
										</div><!--item-footer-->

									</div><!--item-inner-->
								</div><!--item-->
							</div><!--masonry-item-->

						<?php }
					} ?>

				</div><!--masonry-grid-->
			</div><!--item-wrapper-->

			<?php echo $pagination->format(); ?>

		</div><!--main-content-->
	</div><!--main-container-->

<?php require("common/php/php-footer.php"); ?>