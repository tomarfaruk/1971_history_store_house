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
            $page = Helper::post_val("page");
            $images = new Image();

            if($page){
                $start = ($page - 1) * API_PAGINATION;
                $images = $images->orderBy("id")->desc()
                    ->limit($start, API_PAGINATION)->all();
            }else $images = $images->orderBy("id")->desc()->all();

            if(!empty($images)) {
                // foreach ($audios as $audio) {
                //     $audio->audio_title = null;
                //     $audio->audio_url = null;
                //     $audio->audio_img = null;
                //     $audio->audio_description = null;
                //     $audio->id = null;
                // }

                $response->create(200, "Success.", $images);
            }else $response->create(201, "No Video Found.", null);

        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);


echo $response->print_response();

?>
