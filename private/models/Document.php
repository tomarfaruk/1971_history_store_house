<?php

class Document extends Util{

    public $id;
    public $doc_title;
    public $doc_cat_id;
    public $admin_id;
    public $doc_img_url;
    public $doc_file_url;
    public $doc_author;

    

    public function get_category_title(){
        $cat = new Doc_category();
        $cat = $cat->where(["doc_cat_id" => $this->doc_cat_id])->one();
        return $cat->doc_title;
    }
}

?>