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
            $categories = new audio_category();

            if($page){
                $start = ($page - 1) * API_PAGINATION;
                $categories = $categories->orderBy("cat_id")->desc()->limit($start, API_PAGINATION)->all();
            }else $categories = $categories->orderBy("cat_id")->desc()->all();

			
			// foreach($categories as $item){
			// 	$item->created = Helper::days_ago($item->created);
			// }	
				
            if(!empty($categories)) $response->create(200, "Success.", $categories);
            else $response->create(200, "No Category Found.", null);

        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>
