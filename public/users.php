<?php require_once('../private/init.php'); ?>

<?php

$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
    $all_users = new User();
    $pagination = "";
    $pagination_msg = "";

    if(Helper::is_get()){
        $page = Helper::get_val('page');
        if($page){
            $pagination = new Pagination($all_users->count(), BACKEND_PAGINATION, $page, "users.php");
            if(($page > $pagination->get_page_count()) || ($page < 1)) $pagination_msg = "Nothing Found.";
        }else {
            $page = 1;
            $pagination = new Pagination($all_users->count(), BACKEND_PAGINATION, $page, "users.php");
        }
    }

    $start = ($page - 1) * BACKEND_PAGINATION;
    $all_users = $all_users->where(["admin_id" => $admin->id])->orderBy("id")->desc()->limit($start, BACKEND_PAGINATION)->all();

}else  Helper::redirect_to("login.php");

?>

<?php require("common/php/php-head.php"); ?>

<body>

<?php require("common/php/header.php"); ?>

<div class="main-container">

	<?php require("common/php/sidebar.php"); ?>

	<div class="main-content">

		<h4 class="mb-30 mb-xs-15">Registered users</h4>
		<div class="item-wrapper">

            <div class="ml-10"><?php if($message) echo $message->format(); ?></div>

            <?php if(!empty($pagination_msg)) echo $pagination_msg; ?>

            <div class="masonry-grid four">

                <?php if(count($all_users) > 0){
                    foreach ($all_users as $u){ ?>
                    <div class="masonry-item">
                        <div class="item">
                            <div class="item-inner">
                                <div class="item-content">

                                    <?php
                                        if($u->type == 1){
                                            if($u->status == 1) $u->type = "Email<sapan class='verified'>(Verified)</sapan>";
                                            else $u->type = "Email<sapan class='unverified'>(UnVerified)</sapan>";
                                        }else if($u->type == 2) $u->type = "Facebook";
                                        else if($u->type == 3) $u->type = "Gmail";
                                        else $u->type = "Unknown";
                                    ?>
                                    <h6 class="color-semi-dark">Signed in with</h6>
                                    <h6><?php echo $u->type; ?></h6>
                                    <h6 class="mt-15 color-semi-dark">Username</h6>
                                    <h5><?php echo $u->username; ?></h5>
                                    <h6 class="mt-15 color-semi-dark">Email</h6>
                                    <h5><?php echo $u->email; ?></h5>
                                </div><!--item-content-->

                                <div class="item-footer">
                                    <a href="<?php echo '../private/controllers/users.php?id=' .
                                        $u->id . '&&admin_id=' . $u->admin_id; ?>"
                                       data-confirm="Are you sure you want to delete this item?">Delete</a>

                                  </div><!--item-footer-->

                            </div><!--item-inner-->
                        </div><!--item-->
                    </div><!--masonry-grid-->
                    <?php }
                }?>

            </div><!--masonry-grid-->
		</div><!--item-wrapper-->

        <?php echo $pagination->format(); ?>
        
	</div><!--main-content-->
</div><!--main-container-->

<?php require("common/php/php-footer.php"); ?>