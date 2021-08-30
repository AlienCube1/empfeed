<?php
namespace EmpFeed\Core\Database;
use PDO;
class Database{

    // Default data

    protected $host = "";
    protected $dbname = "";
    protected $username = "";
    protected $password = "";
  

    public function __construct(){
        $this->host = "127.0.0.1";
        $this->dbname = "empfeed";
        $this->username = "root";
        $this->password = "";
    }

    // Try connect
    public function connect(){
        try{
            
            $oConnection = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            //echo "Connected to $this->dbname at $this->host successfully.";
            return $oConnection;
        
        }
        catch(PDOException $pe){
            die("Could not connect to the database $this->dbname :" . $pe->getMessage());
        }
    }
    /**
     * Get all fields of a table that gets supplied
     * @param $tableName name of table that gets described
     * 
     * @return $aData array of data of a table.
     */

    public function getAllFields($tableName){
        
        $databaseConnection = $this->connect();
        $query = $databaseConnection->prepare("DESCRIBE $tableName");
        $query->execute();
        $aData = $query->fetchAll(PDO::FETCH_COLUMN);
        return $aData;
    }

}


?>