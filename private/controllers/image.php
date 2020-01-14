<?php require_once('../init.php'); ?>
<?php

$admin = Session::get_session(new Admin());
if(empty($admin)){
    Helper::redirect_to("admin_login.php");
}else{
    $errors = new Errors();
    $message = new Message();
    $image = new Image();

    if (Helper::is_post()) {
        $image->admin_id = $_POST['admin_id'];
        $image->id = Helper::post_val('id');
        $image->img_title = $_POST['title'];

        $image->img_url = $_POST["image_name"];
        $image->img_cat_id = $_POST['category'];
        $image->img_desc = $_POST['description'];
        

        if(!$image->id){
                
            // if(!empty($_FILES["image_name"]["name"])){
            //     $upload = new Upload($_FILES["image_name"]);
            //     $upload->set_max_size(MAX_IMAGE_SIZE);
            //     if($upload->upload()) $image->img_url = $upload->get_file_name();
            //     $errors = $upload->get_errors();
            // }
            

            if($errors->is_empty()){
                if($image->save()) {
                    $message->set_message("image Created Successfully");
                    Helper::redirect_to("../../public/image-form.php");
                }
            }
                
            

            if(!$message->is_empty()){
                Session::set_session($message);
                Helper::redirect_to("../../public/image-form.php");
            }else if(!$errors->is_empty()){
                Session::set_session($errors);
                Helper::redirect_to("../../public/image-form.php");
            }
            
        }
    }else if (Helper::is_get()) {
        
        $image->id = Helper::get_val('id');

            $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            if(mysqli_connect_errno()){
            $message->set_message("database connection error");
            exit();
            }
            
            $sql = "DELETE FROM image WHERE id = ". $image->id;
            $sql = mysqli_real_escape_string($connection, $sql);

            $result = mysqli_query($connection, $sql);
            if($result){
                $message->set_message("image delete successfully");
            }
            else{
                $message->set_message("error on delete");
            }
            
        }else  $errors->add_error("Invalid Notification.");

        if(!$message->is_empty()) Session::set_session($message);
        else Session::set_session($errors);

        Helper::redirect_to("../../public/images.php");
    
}

?>