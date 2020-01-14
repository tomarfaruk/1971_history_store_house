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
            $suggested_videos = new Video();
            $video = new Video();
            $video->id = Helper::post_val("id");
            if($video->id){

                $video = $video->where(["id"=>$video->id])->one();
                if(count($video) > 0){

                    if($page){
                        $start = ($page - 1) * API_PAGINATION;
                        $suggested_videos = $suggested_videos->where(["category"=>$video->category])->andWhere(["status"=>1])
                            ->orderBy("id")->desc()
                            ->limit($start, API_PAGINATION)->all();
                    }else $suggested_videos = $suggested_videos->where(["category"=>$video->category])->andWhere(["status"=>1])
                        ->orderBy("id")->desc()->all();

                    if(count($suggested_videos) > 0){
                        foreach ($suggested_videos as $key => $value){
                            if($value->id == $video->id) {
                                array_splice($suggested_videos, $key, 1);
                                break;
                            }
                        }
                    }

                    if(!empty($suggested_videos)){
                        foreach ($suggested_videos as $video) {
                            $video->created = $video->days_ago();
                            $video->category = $video->get_category_title();
                            $video->description = null;
                            $video->admin_id = null;
                            $video->status = null;
                        }
                        $response->create(200, "Success.", $suggested_videos);
                    }else $response->create(201, "No Video Found.", null);

                }else $response->create(201, "Invalid Video", null);
            }else $response->create(201, "Invalid parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>
