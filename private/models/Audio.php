<?php

class Audio extends Util{

    public $id;
    public $audio_title;
    public $audio_url;
    public $audio_cat_id;
    public $audio_img;
    public $audio_description;
    public $audio_duration;


    public function get_category_title(){
        $cat = new audio_category();
        $cat = $cat->where(["audio_cat_id" => $this->cat_id])->one();
        return $cat->audio_title;
    }
}

?>