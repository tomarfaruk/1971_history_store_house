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

            $favourite = new Favourite();
            $favourite->user_id = Helper::post_val("user_id");

            if($favourite->user_id){
                $page = Helper::post_val("page");
                if($page){
                    $start = ($page - 1) * API_PAGINATION;
                    $fav_arr = $favourite->where(["user_id" => $favourite->user_id])
                        ->orderBy("id")->desc()->limit($start, API_PAGINATION)->all();
                }else $fav_arr = $favourite->where(["user_id" => $favourite->user_id])->orderBy("id")->desc()->all();

                if(count($fav_arr) > 0){

                    $videos = [];
                    foreach ($fav_arr as $item){
                        $vid = new Video();
                        $vid = $vid->where(["id"=>$item->video_id])->one();

                        if((count($vid) > 0)){
                            $vid->created = $vid->days_ago();
                            $vid->category = $vid->get_category_title();

                            if($vid->status == 1) array_push($videos, $vid);
                        }
                    }
                    if(count($videos) > 0)$response->create(200, "Success.", $videos);
                    else $response->create(201, "No Video Found.", null);
                }
                else $response->create(201, "No Item Found.", null);

            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>