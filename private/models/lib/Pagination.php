
<?php

class Pagination{

    private $page_count;
    private $current_page;
    private $url;

    function __construct($total_item, $page_item, $current_page, $url){
        $this->current_page = $current_page;
        $this->url = $url;
        $this->page_count = floor($total_item / $page_item);
        if($total_item % $page_item != 0) $this->page_count ++;
    }

    public function get_page_count(){
        return $this->page_count;
    }

    public function format(){
        $output  = '';
        if($this->page_count > 1){
            $output .= '<ul class="pagination">';
            if($this->current_page > 1) {
                $output .= '<li><a href="' . $this->url . '?page=' . ($this->current_page - 1) . '"><i class="ion-ios-arrow-left"></i><i class="ion-ios-arrow-left"></i></a></li>';
            } else {
                $output .= '<li class="disable"><i class="ion-ios-arrow-left"></i><i class="ion-ios-arrow-left"></i></li>';
            }

            for($i = 1; $i <= $this->page_count; $i++){
                $current_class = "";
                if($this->current_page == $i) {
                    $current_class = "current";
                    $output .= '<li class="' . $current_class . '">' . $i . '</li>';
                }else $output .= '<li class="' . $current_class . '"><a href="' . $this->url . '?page=' . $i . '">' . $i . '</a></li>';
            }

            if($this->current_page < $this->page_count) {
                $output .= '<li><a href="' . $this->url . '?page=' . ($this->current_page + 1) . '"><i class="ion-ios-arrow-right"></i><i class="ion-ios-arrow-right"></i></a></li>';
            } else {
                $output .= '<li class="disable"><i class="ion-ios-arrow-right"></i><i class="ion-ios-arrow-right"></i></li>';
            }
            $output .= '</ul>';
        }
        return $output;
    }

}