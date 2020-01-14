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
            $suggested_livetv = new LiveTV();
            $livetv = new LiveTV();
            $livetv->id = Helper::post_val("id");
            if($livetv->id){

                $livetv = $livetv->where(["id"=>$livetv->id])->one();
                if(count($livetv) > 0){

                    if($page){
                        $start = ($page - 1) * API_PAGINATION;
                        $suggested_livetv = $suggested_livetv->where(["status"=>1])
                            ->orderBy("id")->limit($start, API_PAGINATION)->all();
                    }else $suggested_livetv = $suggested_livetv->where(["status"=>1])->orderBy("id")->all();



                   $current_key = 0;
                    if(count($suggested_livetv) > 0){
                        foreach ($suggested_livetv as $key => $value){
							$value->created  = Helper::days_ago($value->created );
                            if($value->id == $livetv->id)  $current_key = $key;
                        }			
						 array_splice($suggested_livetv, $current_key, 1);
                    }


                    if(!empty($suggested_livetv)) $response->create(200, "Success.", $suggested_livetv);
                    else $response->create(200, "No LiveTV Found.", null);

                }else $response->create(201, "Invalid LiveTV", null);
            }else $response->create(201, "Invalid parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>
