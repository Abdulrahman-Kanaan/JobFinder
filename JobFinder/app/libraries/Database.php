<?php
class Database {

    private $host = DB_HOST;
    private $username = DB_USER;
    private $passwd = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;
    private $dsn;
    private $options;

    public function __construct(){
        $this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $this->options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        );
        // try to connect
        try{
            $this->dbh = new PDO($this->dsn, $this->username, $this->passwd, $this->options);
            // echo 'Connected';
        }
        catch(PDOException $e){
            $this->error = $e->getMessage();
            echo 'Error: ' . $this->error;
        }
    }

    // Prepare statement with query
    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }
    
    // Bind values
    public function bind($param, $value, $type = null){
        if(is_null($type)){
            switch(true){
            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute(){
        return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
    
    // Get row count
    public function rowCount(){
        return $this->stmt->rowCount();
    }
}
?>