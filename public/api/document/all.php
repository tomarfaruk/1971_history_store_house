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
            $documents = new Document();

            if($page){
                $start = ($page - 1) * API_PAGINATION;
                $documents = $documents->orderBy("id")->desc()
                    ->limit($start, API_PAGINATION)->all();
            }else $documents = $documents->orderBy("id")->desc()->all();

            if(!empty($documents)) {
                // foreach ($audios as $audio) {
                //     $audio->audio_title = null;
                //     $audio->audio_url = null;
                //     $audio->audio_img = null;
                //     $audio->audio_description = null;
                //     $audio->id = null;
                // }

                $response->create(200, "Success.", $documents);
            }else $response->create(201, "No Video Found.", null);

        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);


echo $response->print_response();

?>
