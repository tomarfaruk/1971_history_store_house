<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
	$settings = new Setting();
	$settings = $settings->where(["admin_id" => $admin->id])->one();
}else{
    Helper::redirect_to("login.php");
}

?>

<?php require("common/php/php-head.php"); ?>

<body>

<?php require("common/php/header.php"); ?>

<div class="main-container">

	<?php require("common/php/sidebar.php"); ?>

	<div class="main-content">
		<div class="item-wrapper">


			<?php if($message) echo '<div class="ml-15 mt-15">' . $message->format() . '</div>'; ?>

			<div class="masonry-grid three">

				<div class="masonry-item">
					<div class="item">
						<div class="item-inner">

							<h4 class="item-header">APi Token</h4>

							<div class="item-content">
								<form data-validation="true" method="post" action="../private/controllers/setting.php">

									<input type="hidden" name="id" value="<?php echo $settings->id; ?>"/>
									<input type="hidden" name="admin_id" value="<?php echo $settings->admin_id; ?>"/>

									<label>Api Token</label>
									<input data-required="true" type="text" placeholder="eg. bmdk2433xcscww#4gfe" name="api_token"
										   value="<?php echo $settings->api_token; ?>">

									<div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Update</b></button></div>
								</form>

								<?php if(Session::get_session_by_key("type") == "api_token"){
									Session::unset_session_by_key("type");
									if($errors) echo $errors->format();
								}?>

							</div><!--item-content-->

						</div><!--item-inner-->
					</div><!--item-->
				</div><!--masonry-item-->

				<div class="masonry-item">
					<div class="item">
						<div class="item-inner">

							<h4 class="item-header">Popular View Count</h4>

							<div class="item-content">
								<form data-validation="true" method="post" action="../private/controllers/setting.php">

									<input type="hidden" name="id" value="<?php echo $settings->id; ?>"/>
									<input type="hidden" name="admin_id" value="<?php echo $settings->admin_id; ?>"/>

									<label>Minimum view count to be in popular video list</label>
									<input data-required="true" type="text" placeholder="eg. 100" name="popular_view_count" value="<?php echo $settings->popular_view_count; ?>">

									<div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Update</b></button></div>
								</form>

								<?php if(Session::get_session_by_key("type") == "popular_view_count"){
									Session::unset_session_by_key("type");
									if($errors) echo $errors->format();
								}?>

							</div><!--item-content-->

						</div><!--item-inner-->
					</div><!--item-->
				</div><!--masonry-item-->

				<div class="masonry-item">

					<div class="item">
						<div class="item-inner">
							<h4 class="item-header">Download Setting</h4>

							<div class="item-content">
								<form data-validation="true" method="post" action="../private/controllers/setting.php">

									<input type="hidden" name="id" value="<?php echo $settings->id; ?>"/>
									<input type="hidden" name="admin_id" value="<?php echo $settings->admin_id; ?>"/>
									<input type="hidden" name="download_setting" value="1"/>

									<div class="oflow-hidden">
										<h5 class="float-l lh-32">Download Button for Youtube</h5>

										<h5 class="float-r oflow-hidden">
											<label class="download status switch">
												<input type="checkbox" name="download_youtube" <?php if($settings->download_youtube == 1) echo "checked"; ?>/>
												<span class="slider round">
													<b class="active">On</b>
													<b class="inactive">Off</b>
												</span>
											</label>
											<span class="toggle-title"></span>
										</h5>
									</div><!--oflow-hidden-->

									<div class="oflow-hidden">
										<h5 class="float-l lh-32">Download Button for Vimeo</h5>

										<h5 class="float-r oflow-hidden">
											<label class="download status switch">
												<input type="checkbox" name="download_vimeo" <?php if($settings->download_vimeo== 1) echo "checked"; ?>/>
												<span class="slider round">
													<b class="active">On</b>
													<b class="inactive">Off</b>
												</span>
											</label>
											<span class="toggle-title"></span>
										</h5>
									</div><!--oflow-hidden-->

									<div class="oflow-hidden">
										<h5 class="float-l lh-32">Download Button for Uploaded Video</h5>

										<h5 class="float-r oflow-hidden">
											<label class="download status switch">
												<input type="checkbox" name="download_uploaded_video" <?php if($settings->download_uploaded_video == 1) echo "checked"; ?>/>
												<span class="slider round">
													<b class="active">On</b>
													<b class="inactive">Off</b>
												</span>
											</label>
											<span class="toggle-title"></span>
										</h5>
									</div><!--oflow-hidden-->


									<div class="oflow-hidden">
										<h5 class="float-l lh-32">Download Button for Youku</h5>

										<h5 class="float-r oflow-hidden">
											<label class="download status switch">
												<input type="checkbox" name="download_youku" <?php if($settings->download_youku == 1) echo "checked"; ?>/>
												<span class="slider round">
													<b class="active">On</b>
													<b class="inactive">Off</b>
												</span>
											</label>
											<span class="toggle-title"></span>
										</h5>
									</div><!--oflow-hidden-->


									<div class="oflow-hidden">
										<h5 class="float-l lh-32">Download Button for Linked Video</h5>

										<h5 class="float-r oflow-hidden">
											<label class="download status switch">
												<input type="checkbox" name="download_linked_video" <?php if($settings->download_linked_video == 1) echo "checked"; ?>/>
												<span class="slider round">
													<b class="active">On</b>
													<b class="inactive">Off</b>
												</span>
											</label>
											<span class="toggle-title"></span>
										</h5>
									</div><!--oflow-hidden-->

									<div class="btn-wrapper mt-20"><button type="submit" class="c-btn mb-10"><b>Update</b></button></div>
								</form>

								<?php if(Session::get_session_by_key("type") == "download_setting"){
									Session::unset_session_by_key("type");
									if($errors) echo $errors->format();
								}?>

							</div><!--item-content-->

						</div><!--item-inner-->
					</div><!--item-->
				</div><!--masonry-item-->



				<!--<div class="masonry-item">

					<div class="item">
						<div class="item-inner">
							<h4 class="item-header">Download From Link</h4>

							<div class="item-content">
								<form data-validation="true" method="post" action="../private/controllers/setting.php">

									<input type="hidden" name="id" value="<?php echo $settings->id; ?>"/>
									<input type="hidden" name="admin_id" value="<?php echo $settings->admin_id; ?>"/>
									<input type="hidden" name="from_link_download" value="1"/>


									<div class="oflow-hidden">
										<h5 class="float-l lh-32">Download Form Link Option</h5>

										<h5 class="float-r oflow-hidden">
											<label class="download status switch">
												<input type="checkbox" name="download_form_link" <?php if($settings->download_form_link == 1) echo "checked"; ?>/>
												<span class="slider round">
													<b class="active">On</b>
													<b class="inactive">Off</b>
												</span>
											</label>
											<span class="toggle-title"></span>
										</h5>
									</div>

									<div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Update</b></button></div>
								</form>

								<?php if(Session::get_session_by_key("type") == "download_link"){
									Session::unset_session_by_key("type");
									if($errors) echo $errors->format();
								}?>

							</div>

						</div>
					</div>
				</div> -->


				<div class="masonry-item">
					<div class="item">
						<div class="item-inner">

							<h4 class="item-header">Social Login</h4>

							<div class="item-content">
								<form data-validation="true" method="post" action="../private/controllers/setting.php">

									<input type="hidden" name="id" value="<?php echo $settings->id; ?>"/>
									<input type="hidden" name="admin_id" value="<?php echo $settings->admin_id; ?>"/>
									<input type="hidden" name="social_login_config" value="1"/>

									<div class="oflow-hidden">
										<h5 class="float-l lh-32">Social Login Credentials</h5>

										<h5 class="float-r oflow-hidden">
											<label class="download status switch">
												<input type="checkbox" name="social_login_credentials" <?php if($settings->social_login_credentials == 1) echo "checked"; ?>/>
												<span class="slider round">
													<b class="active">On</b>
													<b class="inactive">Off</b>
												</span>
											</label>
											<span class="toggle-title"></span>
										</h5>
									</div>

									<div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Update</b></button></div>
								</form>

								<?php if(Session::get_session_by_key("type") == "social_login"){
									Session::unset_session_by_key("type");
									if($errors) echo $errors->format();
								}?>

							</div>

						</div>
					</div>
				</div>

			</div><!--masonry-grid-->

		</div><!--item-wrapper-->
	</div><!--main-content-->
</div><!--main-container-->



<?php require("common/php/php-footer.php"); ?>