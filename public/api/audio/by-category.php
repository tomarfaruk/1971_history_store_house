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
            $category = Helper::post_val("cat_id");

            if($category){
                $page = Helper::post_val("page");
                $audios = new Audio();

                if($page){
                    $start = ($page - 1) * API_PAGINATION;
                    $audios = $audios->where(["audio_cat_id" => $category])->orderBy("id")->desc()
                        ->limit($start, API_PAGINATION)->all();
                }else $audios = $audios->where(["audio_cat_id" => $category])->orderBy("id")->desc()->all();

                if(!empty($audios)) {
                    // foreach ($videos as $video) {
                    //     $video->created = $video->days_ago();
                    //     $video->category = $video->get_category_title();
                    //     $video->description = null;
                    //     $video->admin_id = null;
                    //     $video->status = null;
                    // }
                    $response->create(200, "Success.", $audios);
                }else $response->create(201, "No Video Found.", null);

            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>