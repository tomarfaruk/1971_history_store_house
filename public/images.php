<?php require_once('../private/init.php'); ?>

<?php
$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());



if(!empty($admin)){
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
     if(mysqli_connect_errno()){
     	$message->set_message("database connection error");
     	exit();
     }
     $sql = "select * from image";
     $sql = mysqli_real_escape_string($connection, $sql);

     $result = mysqli_query($connection, $sql);



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
				<h4 class="float-l mb-15 lh-45 lh-xs-40">Audios</h4>
				<h6 class="float-r mb-15"><b><a class="c-btn" href="image-form.php">+ Add Image</a></b></h6>
			</div>

	

			<div class="item-wrapper">
				<div class="masonry-grid four">

					<?php if(mysqli_num_rows($result) > 0){
						while($row = mysqli_fetch_array($result)){ 
							?>
							<div class="masonry-item">
								<div class="item">
									<div class="item-inner">
										<div class="oflow-hidden mt-15 mb-20">
												<div class="float-l circle-img-50 mr-10 brdr-primary-1">
												<a href="<?php echo $row['img_url']; ?>" target="_blank">
													<img src="<?php echo $row['img_url']; ?>" alt="">
												</a>
												</div>
												<div class="float-l">
													
													<h5><?php echo $row['img_title']; ?>	</h5>
												</div>

												<div class="float-r right-text">
												<a href="<?php echo '../private/controllers/image.php?id=' . $row['id']?>"
                                               data-confirm="Are you sure you want to delete this item?"><i class="ion-trash-a"></i></a>
												</div><!--float-r-->
											</div>
											<!-- PDF Link:
											<a href="uploads/<?php echo $row['doc_file_url']; ?>" target="_blank"><?php echo $row['doc_file_url']; ?></a> -->
											
											<!-- <embed src="uploads/<?php echo $row['doc_file_url']; ?>" type="application/pdf"   height="700px" width="500"> -->
				
				

									</div><!--item-inner-->
								</div><!--item-->
							</div><!--masonry-item-->

						<?php }
					} ?>

				</div><!--masonry-grid-->
			</div><!--item-wrapper-->

			

		</div><!--main-content-->
	</div><!--main-container-->

<?php require("common/php/php-footer.php"); ?>