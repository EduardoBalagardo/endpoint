<?php  

require_once("IDB.php");
class PDOConnection implements IDB {    
    
    private static $_instance = null;
    private $_pdo, $_query, $_error = false, $_result, $_count = 0;    
    
    /** constructor magic method  **/
    private function __construct(){
        try {
            $this->_pdo = new PDO("mysql:host=".  IDB::HOST . ";dbname=" . IDB::DBIS , IDB::USER, IDB::PASS );
        } catch (PDOException $e) {
            die( $e->getMessage() );            
        }                
    }
    
    /** get Instance an use the other metods **/
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new DB();
        }
        return self::$_instance;                
    }
    
    public function query($sql, $params=array() ){        
        $this->_error = false;
        $x=1;
        if( $this->_query = $this->_pdo->prepare($sql) ){
            if(count($params)>0){
                foreach ($params as $param){
                    $this->_query->bindValue($x, $param);
                    $x++;
                }                        
            }                       
            if($this->_query->execute() ){
                $this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();                
            } else {
               $this->_error = true; 
            }
        } 
        return $this;
    }
    
    
    public function action($action, $table, $where = array() ){        
        if(count($where) === 3){            
            $operators = array("=", ">", "<", ">=", "<=");
            $field    = $where[0];
            $operator = $where[1];
            $value    = $where[2];            
            if (in_array($operator, $operators) ){
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, array($value))->error() ){
                    return $this;                    
                }                
            }            
        }                
    }
    
    public function get($table, $where){
        return $this->action("SELECT *", $table, $where);
               
    }
    
    public function delete($table, $where){
        return $this->action("DELETE ", $table, $where);        
    }
    
    public function insert($table, $fields = array()){        
        if( count($fields)> 0 ){
            $keys = array_keys($fields);
            $values = null;
            $x=1;
            foreach($fields as $field){
                $values .= "?";
                if($x<count($fields)){
                    $values .= ", ";
                }
                $x++;
                
            }            
            $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys).  "`) VALUES ({$values})";
            if( !$this->query($sql, $fields)->error()){
                return true;               
            }            
        }
        return false;
    }
    

    public function update($table, $id, $fields = array()){
        $set = "";
        $x = 1;
        foreach($fields as $name=>$value){
            $set .= "{$name} = ?";
            if( $x <count($fields)){
                $set.= ",";                
            }
            $x++;
        }
        $sql = "UPDATE {$table} SET {$set} WHERE idorden_productos = {$id}";
        echo $sql;
        if( !$this->query($sql, $fields)->error()){
            return true;            
        }
        return false;
    }
    
    public function results(){
        return $this->_result;
    }
    
    public function first(){
        return $this->results()[0];
    }
    
    /** Show Error **/
    public function error(){
        return $this->_error;
    }
    
    /** for count result **/
    
    public function count(){
        return $this->_count;
    }

    public function doConnect() { }

    public function filter($data, $type) {
        
    }

}
?>
