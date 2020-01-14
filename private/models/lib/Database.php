<?php

abstract class Database{

    private $db_conn;
    private $sql;
    private $class_name;
    private $where_col;
    private $extended_where;
    private $like_where;
    private $order_by;
    private $limit;

    function __construct(){
        $this->sql = "";
        $this->db_conn = $this->db_connect();
        $this->class_name = strtolower(get_called_class());
        $this->where_col = "";
        $this->extended_where = "";
        $this->like_where = "";
        $this->order_by = "";
        $this->limit = "";
    }

    abstract function getVariables();

    public function where($column){
        if(count($column) == 1){
            $this->where_col .= key($column);
            $this->sql = " WHERE " . key($column) . " = " . $this->formatSql($column[key($column)]);
        }
        return $this;
    }

    public function andWhere($column){
        if(count($column) == 1){
            $this->where_col .= key($column);
            $this->sql .= " AND " . key($column) . " = " . $this->formatSql($column[key($column)]);
        }
        return $this;
    }

    public function not($column){
        if(count($column) == 1){
            $this->extended_where .= " AND NOT " . key($column) . " = " . $this->formatSql($column[key($column)]);
        }
        return $this;
    }

    public function greater($column){
        if(count($column) == 1){
            $this->extended_where .= " AND " . key($column) . " > " . $this->formatSql($column[key($column)]);
        }
        return $this;
    }

    public function greater_eq($column){
        if(count($column) == 1){
            $this->extended_where .= " AND " . key($column) . " >= " . $this->formatSql($column[key($column)]);
        }
        return $this;
    }

    public function less($column){
        if(count($column) == 1){
            $this->extended_where .= " AND " . key($column) . " < " . $this->formatSql($column[key($column)]);
        }
        return $this;
    }

    public function less_eq($column){
        if(count($column) == 1){
            $this->extended_where .= " AND " . key($column) . " < " . $this->formatSql($column[key($column)]);
        }
        return $this;
    }

    public function orderBy($column){
        if(!empty($column)){
            $this->order_by = " ORDER BY " . $column;
        }
        return $this;
    }

    public function andOrderBy($column){
        if(!empty($column)){
            $this->order_by .= ", " . $column;
        }
        return $this;
    }

    public function desc(){
        $this->order_by .= " DESC ";
        return $this;
    }
    
    public function save(){
        $columns = "";
        $values = "";
        foreach($this->getVariables() as $key => $value){
            if(!empty($value)){
                $columns .= $key . ", ";
                $values .= $this->formatSql($value) . ", ";
            }
        }
        $this->sql  = "INSERT INTO " . $this->class_name;
        $this->sql .= " (" . $this->removeLastChar($columns) . ") VALUES ( " . $this->removeLastChar($values) . ")";
        return $this->insert_row();
    }

    public function update(){
        $columns = "";
        foreach($this->getVariables() as $key => $value){
            if(!empty(trim($value))){
                if(strpos($this->where_col, $key) !== false) continue;
                $columns .= $key . "=" . $this->formatSql($value) . ", ";
            }
        }
        $temp = $this->sql;
        $this->sql  = "UPDATE " .  $this->class_name . " SET  ";
        $this->sql .= $this->removeLastChar($columns);
        $this->sql .= $temp;
        return $this->update_row();
    }

    public function delete(){
        $temp = $this->sql;
        $this->sql  = "DELETE FROM " .  $this->class_name . " ";
        $this->sql .= $temp;
        return $this->delete_row();
    }

    public function limit($a, $b){
        $this->limit = " LIMIT " . $a . ", " .$b;
        return $this;
    }

    public function count(){
        $this->sql = "SELECT COUNT(*) FROM " . $this->class_name;
        $result = mysqli_query($this->db_conn, $this->sql);
        $this->confirm_result_set($result);
        $row = mysqli_fetch_row($result);
        if(count($row) > 0) return $row[0];
        else return null;
    }

    public function avg($column_name){
        $statement = "SELECT AVG(" . $column_name . ") FROM " . $this->class_name;
        $this->sql = $statement . $this->sql;
        $result = mysqli_query($this->db_conn, $this->sql);
        $this->confirm_result_set($result);
        $row = mysqli_fetch_row($result);
        if(count($row) > 0) return $row[0];
        else return null;
    }

    public function one(){
        $this->sql = "SELECT * FROM " . $this->class_name . $this->sql . $this->extended_where . $this->order_by . " LIMIT 1";
        return $this->single_row();
    }


   public function like($column){
        if(count($column) == 1){
            $search_arr = explode(" ", $column[key($column)]);

            if(count($search_arr) < 1){
                $this->like_where = " WHERE " . key($column) . " LIKE " . "%" . $this->formatSql($column[key($column)]) . "%";
            }else{
                foreach ($search_arr as $key => $value) {
                    if($key == 0) $this->like_where .= " WHERE " . key($column) . " LIKE " .$this->formatSql("%" . $value . "%");
                    else $this->like_where .= " OR " . key($column) . " LIKE " . $this->formatSql("%" . $value . "%");
                }
            }
            $this->like_where .= "ORDER BY LOCATE('" . $column[key($column)] . "', " . key($column) . ") DESC";
        }
        return $this;
    }

    public function all(){
        if(!empty($this->like_where)) $this->sql = "SELECT * FROM " . $this->class_name . $this->sql . $this->like_where . $this->limit;
        else $this->sql = "SELECT * FROM " . $this->class_name . $this->sql . $this->extended_where . $this->order_by . $this->limit;

        return $this->multiple_rows();
    }

    private function removeLastChar($str){
        $str = trim($str);
        return substr($str, 0, strlen($str) - 1);
    }



    private function formatSql($var){
        if (preg_match("/^\d+$/", $var)) return $var * 1;
        else return "'" . $this->db_escape($var) . "'";
        /*if (!is_numeric($var)) return "'" . $this->db_escape($var) . "'";
        else return $this->db_escape($var);*/
    }

    private function db_connect() {
        $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        $this->confirm_db_connect();
        return $connection;
    }

    private function db_disconnect($connection) {
        if(isset($connection))  $this->mysqli_close($this->db_conn);
    }

    private function db_escape($string) {
        return mysqli_real_escape_string($this->db_conn, $string);
    }

    private  function confirm_db_connect() {
        if(mysqli_connect_errno()) {
            $msg = "Database connection failed: ";
            $msg .= mysqli_connect_error();
            $msg .= " (" . mysqli_connect_errno() . ")";
            exit($msg);
        }
    }

    private function confirm_result_set($result_set) {
        if (!$result_set) exit("Database query failed.");
    }

    private function delete_row(){
        $result = mysqli_query($this->db_conn, $this->sql);
        $this->confirm_result_set($result);
        return $result;
    }

    private function single_row(){
        $result = mysqli_query($this->db_conn, $this->sql);
        $this->confirm_result_set($result);
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        //return (!empty($row)) ? Helper::arrayToObject($row, $this->class_name) : null;
        return (!empty($row)) ? Helper::arrayToObject($row, $this->class_name) : null;
    }

    private function multiple_rows(){
        $result = mysqli_query($this->db_conn, $this->sql);
        $this->confirm_result_set($result);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            array_push($rows, Helper::arrayToObject($row, $this->class_name));
        }
        mysqli_free_result($result);
        return $rows;
    }

    private function update_row(){
        $result = mysqli_query($this->db_conn, $this->sql);
        if($result) return true;
        else {
            echo mysqli_error($this->db_conn);
            $this->db_disconnect($this->db_conn);
            exit;
        }
    }

    private function insert_row(){
        $result = mysqli_query($this->db_conn, $this->sql);
        // For INSERT statements, $result is true/false
        if($result) return mysqli_insert_id($this->db_conn);
        else {
            // INSERT failed
            echo mysqli_error($this->db_conn);
            $this->db_disconnect($this->db_conn);
            exit;
        }
    }
}