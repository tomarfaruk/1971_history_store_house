<?php require_once('../private/init.php'); ?>

<?php

$admin = Session::get_session(new Admin());

if(empty($admin)){
    Helper::redirect_to("login.php");
}

?>



<?php require("common/php/php-head.php"); ?>



<body>

<?php require("common/php/header.php"); ?>

<div class="main-container">


	<?php require("common/php/sidebar.php"); ?>

	<div class="main-content">

		<div class="item-wrapper three">

			<div class="item item-dahboard">
				<div class="item-inner">

					<?php
					$video = new Video();
					$categories = new Category();
					$reviews = new Review();
					$livetvs = new LiveTV();
					$users = new User();

					$videos = $video->all();
					$view_count = 0;
					foreach ($videos as $item) {
						$view_count += 	$item->view_count;
					}
					?>


					<div class="item-content">
						<h2 class="title"><b><?php echo $video->count();?></b></h2>
						<h4 class="desc">Videos</h4>
					</div><!--item-content-->

					<div class="icon"><i class="ion-android-film"></i></div>

					<div class="item-footer">
						<a href="videos.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->

				</div><!--item-inner-->
			</div><!--item-->


			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $view_count; ?></b></h2>
						<h4 class="desc">Views</h4>
					</div>
					<div class="icon"><i class="ion-ios-download"></i></div>
					<div class="item-footer">
						<a href="videos.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->
				</div><!--item-inner-->
			</div><!--item-->

			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $categories->count(); ?></b></h2>
						<h4 class="desc">Categories</h4>
					</div>
					<div class="icon"><i class="ion-social-buffer"></i></div>
					<div class="item-footer">
						<a href="categories.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->
				</div><!--item-inner-->
			</div><!--item-->

			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $livetvs->count(); ?></b></h2>
						<h4 class="desc">LiveTV</h4>
					</div>
					<div class="icon"><i class="ion-android-laptop"></i></div>
					<div class="item-footer">
						<a href="videos.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->
				</div><!--item-inner-->
			</div><!--item-->
			
			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $reviews->count(); ?></b></h2>
						<h4 class="desc">Rating & Review</h4>
					</div>
					<div class="icon"><i class="ion-android-star-half"></i></div>

					<div class="item-footer">
						<a href="videos.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->

				</div><!--item-inner-->
			</div><!--item-->

			<div class="item item-dahboard">
				<div class="item-inner">
					<div class="item-content">
						<h2 class="title"><b><?php echo $users->count(); ?></b></h2>
						<h4 class="desc">Users</h4>
					</div>
					<div class="icon"><i class="ion-android-people"></i></div>
					<div class="item-footer">
						<a href="users.php">More info <i class="ml-10 ion-chevron-right"></i><i class="ion-chevron-right"></i></a>
					</div><!--item-footer-->

				</div><!--item-inner-->
			</div><!--item-->

		</div><!--item-wrapper-->
	</div><!--main-content-->
</div><!--main-container-->


<?php require("common/php/php-footer.php"); ?>