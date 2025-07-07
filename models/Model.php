<?php 
abstract class Model{
    protected static string $table;
    protected static string $primary_key;
    

    public static function find(mysqli $mysqli, int $id){
        $sql = sprintf("Select * from %s WHERE %s = ?", 
                        static::$table, 
                        static::$primary_key);
        
        $query = $mysqli->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();
        
        $data = $query->get_result();
        $objects = [];
        while($row = $data->fetch_assoc()){
            $objects[] = new static($row); //creating an object of type "static" / "parent" and adding the object to the array
        }

        return sizeof($objects) > 0? (sizeof($objects) == 1 ? $objects[0]: $objects) : null;
    }

    public static function all(mysqli $mysqli){
        $sql = sprintf("Select * from %s", static::$table);
        
        $query = $mysqli->prepare($sql);
        $query->execute();

        $data = $query->get_result();

        $objects = [];
        while($row = $data->fetch_assoc()){
            $objects[] = new static($row); 
        }

        return $objects; 
    }

    public static function create(mysqli $mysqli, array $values){
        $column_names = array_keys($values);
        
        $column_values = array_values($values);
       
        $column_names = implode(",",$column_names);
        $valuesToBind = array_fill(0,sizeof($column_values), '?');
        $valuesToBind = implode(",", $valuesToBind);
        $sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", static::$table, $column_names, $valuesToBind);
        
        $bindingDatatypes = static::prepareForBinding($column_values);
        $query = $mysqli->prepare($sql);
        $query->bind_param($bindingDatatypes, ...$column_values);
        
        $query->execute();

        
    }

    public static function update(mysqli $mysqli,array $values, int $id){
        $column_values = array_values($values);
        
        $column_names = array_keys($values);
        $valuesToBind = [];
        foreach($column_names as $key){
            $valuesToBind[] = $key . " = ?"; 
        }
        $valuesToBind = implode(",", $valuesToBind);
        $sql = sprintf("UPDATE %s set %s where %s = ?", static::$table, $valuesToBind, static::$primary_key);
        $query = $mysqli->prepare($sql);
        $bindingDatatypes = static::prepareForBinding($column_values);
        $bindingDatatypes .= "i";
        $column_values[] = $id;

        $query->bind_param($bindingDatatypes, ...$column_values);
        $query->execute();
    }

    public static function delete(mysqli $mysqli, int $id){
        $sql = sprintf("DELETE from %s WHERE %s = ?", 
                        static::$table, 
                        static::$primary_key);
        $query = $mysqli->prepare($sql);
        $query->bind_param("i", $id);
        $query->execute();
    }

    public static function deleteAll(mysqli $mysqli){
        $sql = sprintf("DELETE from %s", static::$table);
        $query = ($mysqli->prepare($sql))->execute();
    }

    private static function prepareForBinding(array $column_values)
    {
        $datatypes = "";
        foreach($column_values as $value){
            if(is_int($value)){
                $datatypes .= 'i';
                
            }
            else if(is_float($value)){
                $datatypes .= 'd';
            }
            else{
                $datatypes .= 's';
            }
        }
        return $datatypes;
    }


    //-------------------------------------------------------------------------------------

    public static function findByValues(mysqli $mysqli, array $values){
        $types = "";
        $params = [];
        $valuesInQuery = static::sqlValueForm($values," and ");
        $sql = sprintf("Select * from %s where %s",static::$table, $valuesInQuery);
        
        $query= $mysqli->prepare($sql);
        static::bindToQuery($values, $types, $params);
        $query->bind_param($types,...$params);
        $query->execute();
        $data = $query->get_result();
        $objects = [];
        while($row = $data->fetch_assoc()){
            $objects[] = new static($row);
        }
        return $objects;
    }

     public static function sqlValueForm(&$values, $separateString){
        $normalArray = [];
        foreach($values as $key => $value){
            $normalArray[] = $key." = ?";
        }
        return implode($separateString,$normalArray);
    }

    public static function bindToQuery(&$values, &$type, &$params){
        $type = "";
        $params = [];
        foreach($values as $key => $value){
            if(is_int($value)){
                $type = $type. 'i';

            }
            else if(is_float($value)){
                $type = $type. 'f';
            }
            else{
                $type = $type. 's';
            }
            $params[] = $value;
        } 
    }

}


