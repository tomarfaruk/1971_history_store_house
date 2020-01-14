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
            $review->user_id = Helper::post_val("user_id");

            if($review->user_id){
                $page = Helper::post_val("page");
                if($page){
                    $start = ($page - 1) * API_PAGINATION;
                    $review_arr = $review->where(["user_id" => $review->user_id])
                        ->orderBy("id")->desc()->limit($start, API_PAGINATION)->all();
                }else $review_arr = $review->where(["user_id" => $review->user_id])->orderBy("id")->desc()->all();

                if(!empty($review_arr)) $response->create(200, "Success.", $review_arr);
                else $response->create(201, "No Review Found.", null);

            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>