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
            $video_id = Helper::post_val("video_id");
            $user_id = Helper::post_val("user_id");

            if($video_id){

                $review = new Review();
                $review = $review->where(['user_id'=>$user_id])->andWhere(['video_id'=>$video_id])->andWhere(['admin_id'=>$setting->admin_id])->one();

                $favourite = new Favourite();
                $favourite = $favourite->where(['user_id'=>$user_id])->andWhere(['video_id'=>$video_id])->andWhere(['admin_id'=>$setting->admin_id])->one();

                $playlist = new Playlist();
                $playlist = $playlist->where(['user_id'=>$user_id])->andWhere(['video_id'=>$video_id])->andWhere(['admin_id'=>$setting->admin_id])->one();

                $check_video = new Check_video();

                if(count($review) > 0) $check_video->review = 1;
                else $check_video->review = 0;

                if(count($favourite) > 0) $check_video->favourite = 1;
                else $check_video->favourite = 0;

                if(count($playlist) > 0) $check_video->playlist = 1;
                else $check_video->playlist = 0;

                $avg_rating = new Review();
                $avg_rating = $avg_rating->where(["video_id"=>$video_id])->all();

                foreach ($avg_rating as $ar){
                    $check_video->avg_rating += $ar->rating;
                    $check_video->total_users ++;
                }
                if($check_video->total_users > 0) $check_video->avg_rating = number_format($check_video->avg_rating / $check_video->total_users, 1);

                $response->create(200, "Success", $check_video);

            }else $response->create(201, "Invalid parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>
