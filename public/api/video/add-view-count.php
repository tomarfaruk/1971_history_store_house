<?php require_once('../../../private/init.php'); ?>

<?php

$response = new Response();
$errors = new Errors();

if(Helper::is_post()){
    $api_token = Helper::post_val("api_token");
    if($api_token){
        $setting = new Setting();
        $setting = $setting->where(["api_token" => $api_token])->one();

        if(!empty($setting)){
            $video = new Video();
            $video->id = Helper::post_val("id");
            $video->admin_id = $setting->admin_id;
            
            if($video->id && $video->admin_id){
                $video = $video->where(["id" => $video->id])->andWhere(["admin_id" => $video->admin_id])->one();
                
                if(count($video) > 0){
                    $new_video = new Video();
                    $new_video->view_count = $video->view_count + 1;

                    if($new_video->where(["id" => $video->id])->update()){
                        $video->view_count = $new_video->view_count;
                         $video->created = null;
						 
                        $response->create(200, "Success.", $video->to_valid_array());
                    }else $response->create(201, "Something Went Wrong. Please try Again.", null);

                }else $response->create(201, "Invalid Video", null);
            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();



	
?>