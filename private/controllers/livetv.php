<?php require_once('../init.php'); ?>
<?php

$admin = Session::get_session(new Admin());
if(empty($admin)){
    Helper::redirect_to("admin_login.php");
}else{

    $errors = new Errors();
    $message = new Message();
    $livetv = new LiveTV();

    if (Helper::is_post()) {
        $livetv->admin_id = $_POST['admin_id'];

        if(empty($_POST['id'])){
            $livetv->title = $_POST['title'];
            $livetv->type = $_POST['type'];
            $livetv->link = $_POST['link'];
            $livetv->m3u8 = $_POST['m3u8'];
            $livetv->rtmp = $_POST['rtmp'];
            $livetv->status = (isset($_POST['status'])) ? 1 : 2;

            $livetv->image_name = $_FILES["image_name"]["name"];
            $livetv->image_resolution = $_POST['image_resolution'];
            $livetv->validate_except(["id", "image_resolution", "link", "m3u8", "rtmp"]);
            $errors = $livetv->get_errors();

            if($errors->is_empty()){
                if(!empty($_FILES["image_name"]["name"])){
                    $upload = new Upload($_FILES["image_name"]);
                    $upload->set_max_size(MAX_IMAGE_SIZE);
                    if($upload->upload()){
                        $livetv->image_name = $upload->get_file_name();
                    }
                    $errors = $upload->get_errors();
                }

                if($errors->is_empty()){
                    if($livetv->save()){
                        $message->set_message("LiveTV Created Successfully");
                    }
                }
            }

            if(!$message->is_empty()){
                Session::set_session($message);
                Helper::redirect_to("../../public/livetvs.php");
            }else if(!$errors->is_empty()){
                Session::set_session($errors);
                Helper::redirect_to("../../public/livetv-form.php");
            }
        }else if(!empty($_POST['id'])){

            $livetv->id = $_POST['id'];
            $livetv->title = $_POST['title'];
            $livetv->type = $_POST['type'];
            $livetv->link = $_POST['link'];
            $livetv->m3u8 = $_POST['m3u8'];
            $livetv->rtmp = $_POST['rtmp'];
            $livetv->status = (isset($_POST['status'])) ? 1 : 2;
            $livetv->image_name = $_POST['prev_image'];
            $livetv->image_resolution = $_POST['image_resolution'];

            $livetv->validate_except(["image_name", "image_resolution", "link", "m3u8", "rtmp"]);
            $errors = $livetv->get_errors();

            if($errors->is_empty()){

                if(!empty($_FILES["image_name"]["name"])){
                    $upload = new Upload($_FILES["image_name"]);
                    if($upload->upload()){
                        $upload->delete($livetv->image_name);
                        $upload->set_max_size(MAX_IMAGE_SIZE);
                        $livetv->image_name = $upload->get_file_name();
                    }
                    $errors = $upload->get_errors();
                }

                if($errors->is_empty()){
                    if($livetv->where(["id"=>$livetv->id])->andWhere(["admin_id" => $livetv->admin_id])->update()){
                        $message->set_message("LiveTV Updated Successfully");
                    }
                }
            }

            if(!$message->is_empty()){
                Session::set_session($message);
                Helper::redirect_to("../../public/livetvs.php");
            }else if(!$errors->is_empty()){
                Session::set_session($errors);
                Helper::redirect_to("../../public/livetv-form.php?id=" . $livetv->id);
            }
        }
    }else if (Helper::is_get()) {

        $livetv->id = Helper::get_val('id');
        $livetv->admin_id = Helper::get_val('admin_id');
        if(!empty($livetv->admin_id) && !empty($livetv->id)){
            if($admin->id == $livetv->admin_id){

                $livetv_from_db = new LiveTV();
                $livetv_from_db = $livetv_from_db->where(["id" => $livetv->id])->one();
                if(count($livetv_from_db) > 0){

                    $livetv_image = $livetv_from_db->image_name;
                    if($livetv->where(["id" => $livetv->id])->andWhere(["admin_id" => $livetv->admin_id])->delete()){

                        $message->set_message("Successfully Deleted.");
                        Upload::delete($livetv_image);

                    }else  $errors->add_error("Error Occurred While Deleting");
                }else  $errors->add_error("Invalid LiveTV");
            }else $errors->add_error("You re only allowed to delete your own data.");
        }else  $errors->add_error("Invalid Notification.");

        if(!$message->is_empty()) Session::set_session($message);
        else Session::set_session($errors);

        Helper::redirect_to("../../public/livetvs.php");
    }
}

?>