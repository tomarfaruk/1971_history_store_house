<?php require_once('../init.php'); ?>

<?php

if(Helper::is_post()){
    $errors = new Errors();
    $message = new Message();
    $settings = new Setting();
    $settings->id = $_POST["id"];
    $settings->admin_id = $_POST["admin_id"];

    $type = [];

    if(isset($_POST["api_token"])){
        $settings->api_token = $_POST["api_token"];
        $settings->validate_with(["id", "admin_id", "api_token"]);
        $errors = $settings->get_errors();

        $type["type"] = "api_token";

        if($errors->is_empty()){
            if($settings->where(["id" => $settings->id])->andWhere(["admin_id" => $settings->admin_id])->update()){
                $message->set_message("Api Token Successfully Updated");
            }
        }
    }else if (isset($_POST["popular_view_count"])){
        $settings->popular_view_count = $_POST["popular_view_count"];
        $settings->validate_with(["id", "admin_id", "popular_view_count"]);
        $errors = $settings->get_errors();

        $type["type"] = "popular_view_count";

        if($errors->is_empty()){
            if($settings->where(["id" => $settings->id])->andWhere(["admin_id" => $settings->admin_id])->update()){
                $message->set_message("Popular View Count Successfully Updated");
            }
        }
    }else if (isset($_POST["download_setting"])){

        $settings->download_youtube = (isset($_POST['download_youtube'])) ? 1 : 2;
        $settings->download_vimeo = (isset($_POST['download_vimeo'])) ? 1 : 2;
        $settings->download_uploaded_video = (isset($_POST['download_uploaded_video'])) ? 1 : 2;
        $settings->download_youku = (isset($_POST['download_youku'])) ? 1 : 2;
        $settings->download_linked_video = (isset($_POST['download_linked_video'])) ? 1 : 2;
        $settings->validate_with(["id", "admin_id", "download_youtube", "download_vimeo", "download_uploaded_video", "download_youku", "download_linked_video"]);
        $errors = $settings->get_errors();

        $type["type"] = "download_setting";

        if($errors->is_empty()){
            if($settings->where(["id" => $settings->id])->andWhere(["admin_id" => $settings->admin_id])->update()){
                $message->set_message("Download Setting Successfully Updated");
            }
        }
    }else if (isset($_POST["from_link_download"])){

        $settings->download_form_link = (isset($_POST['download_form_link'])) ? 1 : 2;

        $settings->validate_with(["id", "admin_id", "download_form_link"]);
        $errors = $settings->get_errors();

        $type["type"] = "download_link";

        if($errors->is_empty()){
            if($settings->where(["id" => $settings->id])->andWhere(["admin_id" => $settings->admin_id])->update()){
                $message->set_message("Download From Youtube Link Config Successfully Updated");
            }
        }
    }else if (isset($_POST["social_login_config"])){

        $settings->social_login_credentials = (isset($_POST['social_login_credentials'])) ? 1 : 2;

        $settings->validate_with(["id", "admin_id", "social_login_credentials"]);
        $errors = $settings->get_errors();

        $type["type"] = "social_login";

        if($errors->is_empty()){
            if($settings->where(["id" => $settings->id])->andWhere(["admin_id" => $settings->admin_id])->update()){
                $message->set_message("Social Login Configuration Successfully Updated");
            }
        }
    }

    Session::set_session($type);

    if(!$message->is_empty()) Session::set_session($message);
    else if(!$errors->is_empty()) Session::set_session($errors);
    
    Helper::redirect_to("../../public/setting.php");
}

?>