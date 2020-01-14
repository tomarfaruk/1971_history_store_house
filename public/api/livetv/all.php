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
            $livetvs = new LiveTV();

            if($page){
                $start = ($page - 1) * API_PAGINATION;
                $livetvs = $livetvs->where(["admin_id" => $setting->admin_id])->andWhere(["status"=>1])
                    ->orderBy("id")->desc()->limit($start, API_PAGINATION)->all();
            }else $livetvs = $livetvs->where(["admin_id" => $setting->admin_id])->andWhere(["status"=>1])
                ->orderBy("id")->desc()->all();


			foreach($livetvs as $item){
				$item->created = Helper::days_ago($item->created);
			}	
			
            if(!empty($livetvs)) $response->create(200, "Success.", $livetvs);
            else $response->create(200, "No LiveTv Found.", null);

        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>
