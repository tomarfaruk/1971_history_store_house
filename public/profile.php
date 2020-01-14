<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
	$admin = $admin->where(["id" => Session::get_session($admin)->id])->one();
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

		<div class="item-wrapper one">

			<div class="item">

                <?php if($message) echo $message->format(); ?>

				<div class="item-inner">
					<h4 class="item-header">Profile</h4>

					<div class="item-content">
						<form data-validation="true" action="../private/controllers/admin.php" method="post">

							<input type="hidden" name="id" value="<?php echo $admin->id; ?>">
							<label>Admin Username</label>
							<input data-required="true" type="text" class="form-control" name="username" value="<?php echo $admin->username; ?>">
							<label>Admin Email</label>
							<input data-required="true" type="text" class="form-control" name="email" value="<?php echo $admin->email; ?>">
							<label>Admin Previous Password</label>
							<input data-required="true" type="password" class="form-control" name="password" value="" placeholder="Enter Previous Password">
							<label>Admin New Password</label>
							<input data-required="true" type="password" class="form-control" name="new_pass" value="" placeholder="Enter New Password">
							<label>Admin Confirm Password</label>
							<input data-required="true" type="password" class="form-control" name="confirm_pass" value="" placeholder="Enter Confirm Password">

							<div class="btn-wrapper"><button type="submit" class="c-btn mb-10"><b>Update</b></button></div>

                            <?php if($errors) echo $errors->format(); ?>
						</form>
					</div><!--item-content-->

				</div><!--item-inner-->
			</div><!--item-->

		</div><!--item-wrapper-->
	</div><!--main-content-->
</div><!--main-container-->



<?php require("common/php/php-footer.php"); ?>