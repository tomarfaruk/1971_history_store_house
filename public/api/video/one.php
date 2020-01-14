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

            if($video->id){

                $video = $video->where(["admin_id" => $setting->admin_id])->andWhere(["id" => $video->id])->andWhere(["status"=>1])->one();
                if(!empty($video)) {
                    $video->created = $video->days_ago();
                    $video->category = $video->get_category_title();
                    $response->create(200, "Success.", $video);
                }else $response->create(201, "No Video Found.", null);

            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>