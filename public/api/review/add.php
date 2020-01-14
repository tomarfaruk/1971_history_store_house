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

            $review = new Review();
            $review->video_id = Helper::post_val("video_id");
            $review->user_id = Helper::post_val("user_id");
            $review->admin_id = $setting->admin_id;
            $review->rating = Helper::post_val("rating");
            $review->review = Helper::post_val("review");
            $review->id = Helper::post_val("id");

            if($review->video_id && $review->user_id && $review->admin_id && $review->rating){
                if($review->rating > 0){
                    if($review->id){
                        if($review->where(["id" => $review->id])->update()){
                            $response->create(200, "Success.", $review->to_valid_array());
                        }else $response->create(201, "Something Wnt Wrong. Please try Again.", null);
                    }else{
                        $review->id = $review->save();
                        if($review->id){
                            $response->create(200, "Success.", $review->to_valid_array());
                        }else $response->create(201, "Something Wnt Wrong. Please try Again.", null);
                    }
                }else $response->create(201, "Make sure rating is valid.", null);
            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>