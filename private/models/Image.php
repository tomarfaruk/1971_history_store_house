<?php

class Image extends Util{

    public $id;
    public $img_title;
    public $img_url;
    public $admin_id;
    public $img_desc;
    public $img_cat_id;

    

    public function get_category_title(){
        $cat = new Img_category();
        $cat = $cat->where(["img_cat_id" => $this->img_cat_id])->one();
        return $cat->doc_title;
    }
}

?>