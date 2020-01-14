<?php require_once('../private/init.php'); ?>

<?php
$errors = Session::get_temp_session(new Errors());
$message = Session::get_temp_session(new Message());
$admin = Session::get_session(new Admin());

if(!empty($admin)){
    $all_categories = new Category();

    $pagination = "";
    $pagination_msg = "";

    if(Helper::is_get()){
        $page = Helper::get_val('page');
        if($page){
            $pagination = new Pagination($all_categories->count(), BACKEND_PAGINATION, $page, "categories.php");
            if(($page > $pagination->get_page_count()) || ($page < 1)) $pagination_msg = "Nothing Found.";
        }else {
            $page = 1;
            $pagination = new Pagination($all_categories->count(), BACKEND_PAGINATION, $page, "categories.php");
        }
    }

    $start = ($page - 1) * BACKEND_PAGINATION;
    $all_categories = $all_categories->where(["admin_id" => $admin->id])->orderBy("id")->desc()->limit($start, BACKEND_PAGINATION)->all();

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
			<h4 class="float-l mb-15 lh-45 lh-xs-40">Categories</h4>
			<h6 class="float-r mb-15"><b><a class="c-btn" href="category-form.php">+ Add Category</a></b></h6>
		</div>

        <?php if(!empty($pagination_msg)) echo $pagination_msg; ?>

		<div class="item-wrapper">
			<div class="masonry-grid four">

                <?php if(count($all_categories) > 0){
                    foreach ($all_categories as $category){ ?>
                        <div class="masonry-item">
                            <div class="item item-img">
                                <div class="item-inner">

                                    <h6 class="all-status"><?php
                                        if($category->status == 1) echo "<span class='active'>Active</span>";
                                        else echo "<span class='inactive'>Inactive</span>"; ?>
                                    </h6>

                                    <h6 class="image-wrapper p-35" href="#">
                                        <img src="<?php echo UPLOADED_FOLDER . DIRECTORY_SEPARATOR . $category->image_name; ?>" alt="image"/></h6>

                                    <h5 class="image-footer"><b><?php echo $category->title; ?></b></h5>

                                    <div class="img-header action-btn-wrapper">
                                        <div class="action-btn-inner">
                                            <a href="<?php echo 'category-form.php?id=' . $category->id; ?>"><i class="ion-compose"></i></a>
                                            <a href="<?php echo '../private/controllers/category.php?id=' . $category->id . '&&admin_id=' . $category->admin_id?>"
                                               data-confirm="Are you sure you want to delete this item?"><i class="ion-trash-a"></i></a>

                                        </div><!--action-btn-inner-->
                                    </div><!--action-btn-wrapper-->

                                </div><!--item-inner-->
                            </div><!--item-->
                        </div><!--masonry-item category-item-->
                <?php }
                } ?>

			</div><!--masonry-grid-->
		</div><!--item-wrapper-->

        <?php echo $pagination->format(); ?>

	</div><!--main-content-->
</div><!--main-container-->

<?php require("common/php/php-footer.php"); ?>