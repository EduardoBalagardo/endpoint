<?php
require_once 'IDB.php';
require_once 'DAO.php';
class DataBase extends DAO implements IDB {
    
    
    private $arr = array();
    private $_connection;
    // Store the single instance.     

    /**
     * Constructor.
     */
    public function __construct( $db ) {
        $this->_connection = new mysqli(IDB::HOST,  IDB::USER, IDB::PASS, $this->filter($db , "string") );
        // Error handling.
        if (mysqli_connect_error()) {
            trigger_error('Failed to connect to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);
        }
    }

    /// Retrivering Data Onto Query
    public function get($sql) {
        $this->arr = array();
        $query = mysqli_query($this->_connection, $sql);
        if (!$query === false){                            
            while ($row = mysqli_fetch_array($query)) {                
                array_push($this->arr, $row);
            }
        }        
        return $this->arr;
    }
    
    /// Insert Data Onto Query 
    public function put($sql){
        $query  = mysqli_query($this->_connection, $sql);        
        if($query === true){
            $f = true;
        }else{
            $f = false;
        }       
        //mysqli_close($this->_connection);
        
        return $f;
    }
    
    //get id Row Insert 
    public function getIdRow($sql){
        $query  = mysqli_query($this->_connection, $sql);
        $f = ( $query === true ? true : false );
        $arr = array('status'=>$f, 'id'=> mysqli_insert_id($this->_connection) );
        //mysqli_close($this->_connection);
        return $arr;                
    }
    
    public function clsCnn(){
        mysqli_close($this->_connection);
    }
    
    /**
     * Empty clone magic method to prevent duplication. 
     */
    private function __clone() {
        
    }
    
    /**
     * Get the mysqli connection. 
     
    private function getConnection() {
        return $this->_connection;
    }
    */
    /**
     * Filter Vars for all app Absctract 
     * @param type $data
     * @param type $type
     * @return type
     */
    public function filter($data, $type) {        
        if( $type === 'string' ){
            return filter_var( $data, FILTER_SANITIZE_STRING) ;
        } else if( $type === 'int' ){
            return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
        } else if( $type === 'float'){
            return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT);
        }        
    }

}
