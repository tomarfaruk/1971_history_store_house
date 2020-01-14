<?php require_once('../private/init.php'); ?>

<?php
$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
    $all_livetvs = new LiveTV();

    $pagination = "";
    $pagination_msg = "";

    if(Helper::is_get()){
        $page = Helper::get_val('page');
        if($page){
            $pagination = new Pagination($all_livetvs->count(), BACKEND_PAGINATION, $page, "livetvs.php");
            if(($page > $pagination->get_page_count()) || ($page < 1)) $pagination_msg = "Nothing Found.";
        }else {
            $page = 1;
            $pagination = new Pagination($all_livetvs->count(), BACKEND_PAGINATION, $page, "livetvs.php");
        }
    }

    $start = ($page - 1) * BACKEND_PAGINATION;
    $all_livetvs = $all_livetvs->where(["admin_id" => $admin->id])->orderBy("id")->desc()->limit($start, BACKEND_PAGINATION)->all();

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
			<h4 class="float-l mb-15 lh-45 lh-xs-40">LiveTV</h4>
			<h6 class="float-r mb-15"><b><a class="c-btn" href="livetv-form.php">+ Add LiveTV</a></b></h6>
		</div>

        <?php if(!empty($pagination_msg)) echo $pagination_msg; ?>

		<div class="item-wrapper">
			<div class="masonry-grid four">

                <?php if(count($all_livetvs) > 0){
                    foreach ($all_livetvs as $livetv){ ?>

                        <div class="masonry-item">
                            <div class="item">

                                <div class="item-inner">

                                    <div class="video-header">
                                        <h6 class="all-status video-status"><?php
                                            if($livetv->status == 1) echo "<span class='active'>Active</span>";
                                            else echo "<span class='inactive'>Inactive</span>"; ?>
                                        </h6>

                                    </div>

                                    <div class="p-25">

                                        <div class="two-sided-80">
                                            <div class="left">
                                                <img src="<?php echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR . $livetv->image_name; ?>" alt="image"/>
                                            </div>
                                            <div class="right">
                                                <p class=""><?php echo $livetv->title; ?></p>
                                                <?php
                                                    if($livetv->type == 1) $livetv_type = "Youtube";
                                                    else if($livetv->type == 2) $livetv_type = "M3U8";
                                                    else if($livetv->type == 3) $livetv_type = "RTMP";
                                                    else $livetv_type = "Unknown";
                                                ?>
                                                <p class=""><span class="pre-text">Type : </span><?php echo $livetv_type; ?></p>
                                            </div>
                                        </div><!--livetv-->

                                        <h6><a class="link mt-15" target="_blank" href="<?php echo $livetv->link; ?>">Live Link</a></h6>

                                    </div><!--item-inner-->

                                    <div class="item-footer two">
                                        <a href="<?php echo 'livetv-form.php?id=' . $livetv->id; ?>"><i class="ion-compose"></i></a>
                                        <a href="<?php echo '../private/controllers/livetv.php?id=' . $livetv->id . '&&admin_id=' . $livetv->admin_id?>"
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