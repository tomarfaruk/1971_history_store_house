<?php require_once('../init.php'); ?>
<?php

$admin = Session::get_session(new Admin());
if(empty($admin)){
    Helper::redirect_to("admin_login.php");
}else{
    $errors = new Errors();
    $message = new Message();
    $document = new Document();

    if (Helper::is_post()) {
        $document->admin_id = $_POST['admin_id'];
        $document->id = Helper::post_val('id');
        $document->doc_title = $_POST['title'];

        $document->doc_img_url = $_POST["image_name"];
        $document->doc_cat_id = $_POST['category'];
        $document->doc_author = $_POST['author'];
        $document->doc_file_url = $_POST['file_url'];
        

        if(!$document->id){
                
//             if(!empty($_FILES["image_name"]["name"])){
//                 $upload = new Upload($_FILES["image_name"]);
//                 $upload->set_max_size(MAX_IMAGE_SIZE);
//                 if($upload->upload()) $document->doc_img_url = $upload->get_file_name();
//                 $errors = $upload->get_errors();
//             }
//             if(!empty($_FILES["file_url"]["name"])){
//                 $real_type = $_FILES["file_url"]["type"];
// //                die($real_type);
//                 if($real_type == "application/pdf"){
//                     $file_name = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 16).".pdf";

//                     if(move_uploaded_file($_FILES['file_url']['tmp_name'], '../../public/uploads/'.$file_name )){
//                     $message->set_message("document Created Successfully"); 
//                     $document->doc_file_url = $file_name;                
//                     }else{
//                         $message->set_message("some error occurred");
//                     }
//                 }
//                 else{
//                     $message->set_message("file type must be pdf formate"); 
//                 }
                
//                 Helper::redirect_to("../../public/document-form.php");
//             }


            if($errors->is_empty()){
                if($document->save()) {
                    $message->set_message("document Created Successfully");
                    Helper::redirect_to("../../public/document-form.php");
                }
            }
                
            if(!$message->is_empty()){
                Session::set_session($message);
                Helper::redirect_to("../../public/document-form.php");
            }else if(!$errors->is_empty()){
                Session::set_session($errors);
                Helper::redirect_to("../../public/document-form.php");
            }
            
        }
    }else if (Helper::is_get()) {
        
        $document->id = Helper::get_val('id');

            $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            if(mysqli_connect_errno()){
            $message->set_message("database connection error");
            exit();
            }
            
            $sql = "DELETE FROM document WHERE id = ". $document->id;
            $sql = mysqli_real_escape_string($connection, $sql);

            $result = mysqli_query($connection, $sql);
            if($result){
                $message->set_message("document delete");
            }
            else{
                $message->set_message("error on delete");
            }
            
        }else  $errors->add_error("Invalid Notification.");

        if(!$message->is_empty()) Session::set_session($message);
        else Session::set_session($errors);

        Helper::redirect_to("../../public/documents.php");
    
}

?>