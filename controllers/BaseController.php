<?php 

require_once(__DIR__ . "/../connection/connection.php");

class BaseController{
    protected string $table_name;

    protected mysqli $mysqli;
    public function __construct($table_name){
        $this->table_name = $table_name;
        global $mysqli;
        $this->mysqli = $mysqli;
    }
    public function getColumnNames(){
        global $mysqli;
        $sql = sprintf('SHOW COLUMNS FROM %s', $this->table_name);
        $query = $mysqli->prepare($sql);
        $query->execute();
        $data = $query->get_result();
        $objects = [];
        while($row = $data->fetch_assoc()){
            $objects[] = $row["Field"]; 
        }
        return $objects;

    }

    
}