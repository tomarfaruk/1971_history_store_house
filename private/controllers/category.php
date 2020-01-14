<?php require_once('../init.php'); ?>
<?php

$admin = Session::get_session(new Admin());
if(empty($admin)){
    Helper::redirect_to("admin_login.php");
}else{

    $errors = new Errors();
    $message = new Message();
    $category = new Category();

    if (Helper::is_post()) {
        $category->admin_id = $_POST['admin_id'];

        if(empty($_POST['id'])){
            $category->title = $_POST['title'];
            $category->status = (isset($_POST['status'])) ? 1 : 2;

            $category->image_name = $_FILES["image_name"]["name"];
            $category->image_resolution = $_POST['image_resolution'];

            $category->validate_except(["id", "image_resolution"]);
            $errors = $category->get_errors();

            if($errors->is_empty()){
                if(!empty($_FILES["image_name"]["name"])){
                    $upload = new Upload($_FILES["image_name"]);
                    $upload->set_max_size(MAX_IMAGE_SIZE);
                    if($upload->upload()){
                        $category->image_name = $upload->get_file_name();
                    }
                    $errors = $upload->get_errors();
                }

                if($errors->is_empty()){
                    if($category->save()){
                        $message->set_message("Category Created Successfully");
                    }
                }
            }

            if(!$message->is_empty()){
                Session::set_session($message);
                Helper::redirect_to("../../public/categories.php");
            }else if(!$errors->is_empty()){
                Session::set_session($errors);
                Helper::redirect_to("../../public/category-form.php");
            }

        }else if(!empty($_POST['id'])){

            $category->id = $_POST['id'];
            $category->title = $_POST['title'];
            $category->status = (isset($_POST['status'])) ? 1 : 2;
            $category->image_name = $_POST['prev_image'];
            $category->image_resolution = $_POST['image_resolution'];

            $category->validate_except(["image_name", "image_resolution"]);
            $errors = $category->get_errors();

            if($errors->is_empty()){

                if(!empty($_FILES["image_name"]["name"])){
                    $upload = new Upload($_FILES["image_name"]);
                    $upload->set_max_size(MAX_IMAGE_SIZE);
                    if($upload->upload()){
                        $upload->delete($category->image_name);
                        $category->image_name = $upload->get_file_name();
                    }
                    $errors = $upload->get_errors();
                }

                if($errors->is_empty()){
                    if($category->where(["id"=>$category->id])->andWhere(["admin_id" => $category->admin_id])->update()){
                        $message->set_message("Category Updated Successfully");
                    }
                }
            }

            if(!$message->is_empty()){
                Session::set_session($message);
                Helper::redirect_to("../../public/categories.php");
            }else if(!$errors->is_empty()){
                Session::set_session($errors);
                Helper::redirect_to("../../public/category-form.php?id=" . $category->id);
            }
        }
    }else if (Helper::is_get()) {




        $category->id = Helper::get_val('id');
        $category->admin_id = Helper::get_val('admin_id');
        if(!empty($category->admin_id) && !empty($category->id)){
            if($admin->id == $category->admin_id){

                $category_from_db = new Category();
                $category_from_db = $category_from_db->where(["id" => $category->id])->one();
                if(count($category_from_db) > 0){
                    
                    $category_image = $category_from_db->image_name;
                    if($category->where(["id" => $category->id])->andWhere(["admin_id" => $category->admin_id])->delete()){

                        $message->set_message("Successfully Deleted.");
                        Upload::delete($category_image);
                        $cat_videos = new Video();
                        $cat_videos = $cat_videos->where(["category"=> $category->id])->andWhere(["admin_id"=>$category->admin_id])->all();

                        if(count($cat_videos) > 0){
                            foreach ($cat_videos as $item){
                                $vid = new Video();
                                $video_image = $item->image_name;
                                $main_video = $item->uploaded_video;
                                if($vid->where(["id"=>$item->id])->delete()){
                                    Upload::delete($video_image);
                                    Upload::delete($main_video);
                                }
                            }
                        }

                    }else  $errors->add_error("Error Occurred While Deleting");
                }else  $errors->add_error("Invalid Category");
            }else $errors->add_error("You re only allowed to delete your own data.");
        }else  $errors->add_error("Invalid Notification.");

        if(!$message->is_empty()) Session::set_session($message);
        else Session::set_session($errors);

        Helper::redirect_to("../../public/categories.php");
    }
}

?>