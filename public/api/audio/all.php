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
            $audios = new Audio();

            if($page){
                $start = ($page - 1) * API_PAGINATION;
                $audios = $audios->orderBy("id")->desc()
                    ->limit($start, API_PAGINATION)->all();
            }else $audios = $audios->orderBy("id")->desc()->all();

            if(!empty($audios)) {
                foreach ($audios as $audio) {
                    // $audio->audio_url = UPLOAD_FOLDER . $audio->audio_url;
                    // $audio->audio_img = UPLOAD_FOLDER . $audio->audio_img;                    
                }

                $response->create(200, "Success.", $audios);
            }else $response->create(201, "No Video Found.", null);

        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);


echo $response->print_response();

?>
