<?php require_once('../init.php'); ?>
<?php

$admin = Session::get_session(new Admin());
if(empty($admin)){
    Helper::redirect_to("admin_login.php");
}else{
    $errors = new Errors();
    $message = new Message();
    $video = new Video();

    if (Helper::is_post()) {
        $video->admin_id = $_POST['admin_id'];
        $video->id = Helper::post_val('id');
        $video->title = $_POST['title'];
        $video->status = (isset($_POST['status'])) ? 1 : 2;

        $video->image_name = $_FILES["image_name"]["name"];
        $video->category = $_POST['category'];
        $video->description = $_POST['description'];
        $video->type = $_POST['type'];
        $video->uploaded_video = $_POST['uploaded_video'];
        $video->youtube = $_POST['youtube'];
        $video->vimeo = $_POST['vimeo'];
        $video->youku = $_POST['youku'];
        $video->video_link = $_POST['video_link'];
        $video->featured = (isset($_POST['featured'])) ? 1 : 2;
        $video->image_resolution = $_POST['image_resolution'];
        $video->duration = $_POST['duration'];
        $video->created = date('Y-m-d h:i:s', time());

        if(!$video->id){
            $video->validate_except(["id", "youtube", "vimeo", "duration", "uploaded_video", "youku", "video_link", "view_count", "image_resolution", "created"]);
            $errors = $video->get_errors();

            if($errors->is_empty()){
                if($video->type == 1) $video->validate_with(["youtube"]);
                else if($video->type == 2) $video->validate_with(["vimeo"]);
                else if($video->type == 3) $video->validate_with(["duration", "uploaded_video"]);
                $errors = $video->get_errors();
                
                if($errors->is_empty()){
                    if(!empty($_FILES["image_name"]["name"])){
                        $upload = new Upload($_FILES["image_name"]);
                        $upload->set_max_size(MAX_IMAGE_SIZE);
                        if($upload->upload()) $video->image_name = $upload->get_file_name();
                        $errors = $upload->get_errors();
                    }

                    if($errors->is_empty()){
                        if($video->save()) {
                            $message->set_message("Video Created Successfully");
                        }
                    }
                }
            }

            if(!$message->is_empty()){
                Session::set_session($message);
                Helper::redirect_to("../../public/videos.php");
            }else if(!$errors->is_empty()){
                Session::set_session($errors);
                Helper::redirect_to("../../public/video-form.php");
            }
            
        }else if($video->id){
            $video->validate_except(["image_name", "youtube", "vimeo", "duration", "uploaded_video", "youku", "video_link", "view_count", "image_resolution", "created"]);
            $errors = $video->get_errors();
            
            if($errors->is_empty()){
                if($video->type == 1) $video->validate_with(["youtube"]);
                else if($video->type == 2) $video->validate_with(["vimeo"]);
                else if($video->type == 3) $video->validate_with(["duration", "uploaded_video"]);
                $errors = $video->get_errors();

                if($errors->is_empty()){
                    if(!empty($_FILES["image_name"]["name"])){
                        $upload = new Upload($_FILES["image_name"]);
                        $upload->set_max_size(MAX_IMAGE_SIZE);
                        if($upload->upload()) $video->image_name = $upload->get_file_name();
                        $errors = $upload->get_errors();
                    }

                    if($errors->is_empty()){
                        if($video->where(["id" => $video->id])->update()) $message->set_message("Video Updated Successfully");
                        else $errors->add_error("Something Went Wrong. Please Try Again.");
                    }
                }
            }

            if(!$message->is_empty()){
                Session::set_session($message);
                Helper::redirect_to("../../public/videos.php");
            }else if(!$errors->is_empty()){
                Session::set_session($errors);
                Helper::redirect_to("../../public/video-form.php?id=" . $video->id);
            }

        }
    }else if (Helper::is_get()) {
        
        $video->id = Helper::get_val('id');
        $video->admin_id = Helper::get_val('admin_id');

        if(!empty($video->admin_id) && !empty($video->id)){
            if($admin->id == $video->admin_id){

                $video_from_db = new Video();
                $video_from_db = $video_from_db->where(["id" => $video->id])->one();

                if(count($video_from_db) > 0){
                    $video_image = $video_from_db->image_name;
                    $main_video = $video_from_db->uploaded_video;
                    if($video->where(["id" => $video->id])->andWhere(["admin_id" => $video->admin_id])->delete()){

                        $message->set_message("Successfully Deleted.");
                        Upload::delete($video_image);
                        Upload::delete($main_video);

                    }else  $errors->add_error("Error Occurred While Deleting");
                }else $errors->add_error("Invalid Video");
            }else $errors->add_error("You re only allowed to delete your own data.");
        }else  $errors->add_error("Invalid Notification.");

        if(!$message->is_empty()) Session::set_session($message);
        else Session::set_session($errors);

        Helper::redirect_to("../../public/videos.php");
    }
}

?>