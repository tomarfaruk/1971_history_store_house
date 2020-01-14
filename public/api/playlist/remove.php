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
            $playlist = new Playlist();
            $playlist->video_id = Helper::post_val("video_id");
            $playlist->user_id = Helper::post_val("user_id");

            if(($playlist->video_id) && ($playlist->user_id)){

                if($playlist->where(["video_id" => $playlist->video_id])->andWhere(["user_id" => $playlist->user_id])->delete()){
                    $response->create(200, "Success.", $playlist->to_valid_array());
                }else $response->create(201, "Something Wnt Wrong. Please try Again.", null);

            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>