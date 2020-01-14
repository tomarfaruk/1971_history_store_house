<?php

class Video extends Util{

    public $id;
    public $category;
    public $admin_id;
    public $status;
    public $title;
    public $description;
    public $image_name;
    public $type;
    public $uploaded_video;
    public $youtube;
    public $vimeo;
    public $youku;
    public $video_link;
    public $featured;
    public $view_count;
    public $image_resolution;
    public $duration;
    public $created;

    public function days_ago(){
        $ts1 = strtotime($this->created);
        $ts2 = strtotime(date(DATE_FORMAT, time()));
        $distance = floor(($ts2 - $ts1) / (24 * 60 * 60));
        return ($distance > -1) ? $distance : 0;
    }

    public function get_category_title(){
        $cat = new Category();
        $cat = $cat->where(["id" => $this->category])->one();
        return $cat->title;
    }
}

?>