<?php require_once('../init.php'); ?>
<?php

$admin = Session::get_session(new Admin());
if(empty($admin)){
    Helper::redirect_to("admin_login.php");
}else{
    $errors = new Errors();
    $message = new Message();
    $audio = new Audio();

    if (Helper::is_post()) {
        $audio->id = Helper::post_val('id');
        $audio->audio_title = $_POST['title'];

        $audio->audio_img = $_POST["image_name"];
        $audio->audio_url = $_POST["audio_url"];
        $audio->audio_cat_id = $_POST['category'];
        $audio->audio_description = $_POST['description'];
        $audio->audio_duration = $_POST['audio_duration'];
        

        if(!$audio->id){
                
            // if(!empty($_FILES["image_name"]["name"])){
            //     $upload = new Upload($_FILES["image_name"]);
            //     $upload->set_max_size(MAX_IMAGE_SIZE);
            //     if($upload->upload()) $audio->audio_img = $upload->get_file_name();
            //     $errors = $upload->get_errors();
            // }
            // if(!empty($_FILES["audio_url"]["name"])){
            //     echo $_FILES["audio_url"]["name"];
            //     echo $file_name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 16).".mp3";

            //     if(move_uploaded_file($_FILES['audio_url']['tmp_name'], '../../public/uploads/'.$file_name )){
            //     $message->set_message("audio Created Successfully"); 
            //     $audio->audio_url = $file_name;                
            //     }else{
            //         $message->set_message("errors Created Successfully");
            //     }
            //     Helper::redirect_to("../../public/audio-form.php");
            // }

            if($errors->is_empty()){
                if($audio->save()) {
                    $message->set_message("audio Created Successfully");
                    Helper::redirect_to("../../public/audio-form.php");
                }
            }
                
            

            if(!$message->is_empty()){
                Session::set_session($message);
                Helper::redirect_to("../../public/audio-form.php");
            }else if(!$errors->is_empty()){
                Session::set_session($errors);
                Helper::redirect_to("../../public/audio-form.php");
            }
            
        }
    }else if (Helper::is_get()) {
        
        $audio->id = Helper::get_val('id');

            $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            if(mysqli_connect_errno()){
            $message->set_message("database connection error");
            exit();
            }
            
            $sql = "DELETE FROM audio WHERE id = ". $audio->id;
            $sql = mysqli_real_escape_string($connection, $sql);

            $result = mysqli_query($connection, $sql);
            if($result){
                $message->set_message("audio delete");
            }
            else{
                $message->set_message("error on delete");
            }

                // if(count(array($audio_from_db)) > 0){
                    
                //     if($audio_from_db->where(["id" => $audio->id])->delete()){
                //         $message->set_message("Successfully Deleted.");

                //     }else  $errors->add_error("Error Occurred While Deleting");
                // }else $errors->add_error("Invalid Video");
            
        }else  $errors->add_error("Invalid Notification.");

        if(!$message->is_empty()) Session::set_session($message);
        else Session::set_session($errors);

        Helper::redirect_to("../../public/audios.php");
    
}

?>