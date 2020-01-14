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
            if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["type"])
                && isset($_POST["social_id"]) && isset($_POST["username"])){

                $user = new User();
                $user->email = trim($_POST["email"]);
                $user->password = trim($_POST["password"]);
                $user->type = trim($_POST["type"]);
                $user->social_id = trim($_POST["social_id"]);
                $user->username = trim($_POST["username"]);
                $user->admin_id = $setting->admin_id;

                if($user->type == 1){

                    /*EMAIL LOGIN*/
                    $user->validate_with(["email", "password", "username"]);
                    $errors = $user->get_errors();
                    if($errors->is_empty()){
                        
                        $user_from_db = $user->where(["email" => $user->email])->one();
                        if(empty($user_from_db)){
                            $user->id = $user->save();
                            if(!empty($user->id)){

                                $mailer = new Mailer($user);
                                if($mailer->send()){

                                    $response->create(200, "Success", $user->response()->to_valid_array());

                                }else $response->create(201, "Something Went Wrong", null);
                            }else $response->create(201, "Something Went Wrong", null);
                        }else if($user_from_db->status != 1){
                            if($user->where(["id"=>$user_from_db->id])->update()){
                                $mailer = new Mailer($user);
                                if($mailer->send()){

                                    $response->create(200, "Success", $user->response()->to_valid_array());

                                }else $response->create(201, "Something Went Wrong", null);
                            }else $response->create(201, "Something Went Wrong", null);

                        }else if($user_from_db->status == 1) $response->create(201, "You Already Have an Account", null);
                    }else $response->create(201, $errors->get_error_str(), null);
                }else if(($user->type == 2) || ($user->type == 3)){
                    
                    /*SOCIAL LOGIN*/
                    $user->validate_with(["social_id"]);
                    $errors = $user->get_errors();
                    
                    if($errors->is_empty()){
                        $existing_user = $user->where(["type" => $user->type])->andWhere(["social_id" => $user->social_id])->one();
                        if(!empty($existing_user)){
                            $existing_user->verification_token = "";
                            $response->create(200, "Success", $existing_user->to_valid_array());
                        }else{

                            $user->id = $user->save();
                            if(!empty($user->id)){
                                $response->create(200, "Success", $user->to_valid_array());
                            }else $response->create(201, "Something Went Wrong", null);
                        }
                    }else $response->create(201, $errors, null);
                }else $response->create(201, "Invalid User Type", null);
            }else $response->create(201, "Invalid Parameter", null);
        }else $response->create(201, "Invalid Api Token", null);
    }else $response->create(201, "No Api Token Found", null);
}else $response->create(201, "Invalid Request Method", null);

echo $response->print_response();

?>
