<?php
namespace EmpFeed\Core;

use PDO;
Use EmpFeed\Core\Utilities\Util;
Use EmpFeed\Core\Database\Database;
Use EmpFeed\Core\Utilities\ExceptionHandler;
/**
 * Model class which hosts functions needed so that the user doesn't have to add all methods to controllers
 * Every class that gets inherited from this class should have its
 * first data member set as 'table_name'
 *
 */

class Model{
    
    protected $defaultUserTable;
    
    protected $userConstructArray = array();
   

    public function __construct($arrayOfObjects = array()){
        $this->defaultUserTable = Util::getEnv('default_user_table');
    }
    /**
     * Construct child from parent
     * uses array of objects that are used to create child
     * regular constructor can be called without this method
     * If inherited class has an array called protected, that array can contain
     * all the data members that CAN'T be constructed by means of mass alignment
     */
    public function constructChild($arrayOfObjects){
        $this->userConstructArray = $arrayOfObjects;
        
        foreach($this->userConstructArray as $key=>$value){
            if(in_array($key, $this->protected)){
                echo "Can't set value for a non-fillable object!";
            }
            else{
                $this->{$key} = $value;
            }
        }
        
    }

    /**
     * Return instance of itself
     * 
     * @return instance of self
     */
    public function returnSelfInstance(){
        return $this;
    }
    /**
     * Save Model to relevant database.
     * This method is also used for storing Users after registration
     * User does not have to do anything, method checks if there is an email, username, etc.
     * And then stores it accordingly
     * @param bool $bCheckIfCredentialExists if this variable is set to true, it checks does a credential exist in table.
     * @param bool $useID this variable decides if the id column (first col) is used in models
     * If model is used to automaticalyl increment IDS, this should be set to true.
     * If set to false, it does not check.
     * Meaning that username, email or else does not have to be unique.
     * By default it's left as true, which is recommended for most projects.
     * 
     * @return bool $bool Message of status.
     */

    public function save($bCheckIfCredentialExists = true, $useID = false){
        
        $selfInstance = $this;
        // Array for errors that get returned TO API endpoint
        $aEndPointErrors = array();

        // Array that hosts keys from the model. (Names of data members)
        $argArrayKeys = array();
        
        // Array that hosts values from the model. (Values of data members)
        $argArrayValues = array();
        
        $database = new Database();
        $databaseConnection = $database->connect();
        
        // Counter that is used to check for extra variables, E.G isAdmin, isEtc.
        $nExtraCounter = 0;
        // Temporary variable that gets bound to the E-mail of save function;
        // This only gets used in case that save is used for saving users.
        // credential that gets checked is set inside .env E.G - 'default_user_email'
        // credential does not have to be E-mail, it can be anything, E.G -'username'
        $credential = "";
        
        foreach($selfInstance as $singularInstance=> $valueOfSingularInstance){
            if($singularInstance == 'tableName' ||$singularInstance == 'defaultUserTable' ||$singularInstance == 'userParams'){
                continue;
            } 
            else{
                if($singularInstance == Util::getEnv('default_user_auth_name')){
                    $credential = $valueOfSingularInstance;
                    $nExtraCounter+=1;
                }
                array_push($argArrayKeys, $singularInstance);
                array_push($argArrayValues, $valueOfSingularInstance);
            }
        }
        
        if($bCheckIfCredentialExists){
        $credentialAlredyExists = $this->fetch(Util::getEnv('default_user_table'), Util::getEnv('default_user_auth_name'), $credential);
        

        // Variable used to check wheter the table that we are trying to insert into
        // Is same as the the described table with the same name.
        $tablesMatch = false;
            if($credentialAlredyExists){
                echo "That E-mail is alredy registered, try loging in!";
                $sErrorString = "That E-mail is alredy registered, try loging in!";
                array_push($aEndPointErrors, $aEndPointErrors);
            }
            else{
                $tableName = $selfInstance->tableName;
                $aDescribedTable = $database->getAllFields($tableName);
                
                // Loop checks if the values that we fetched are same as the described
                // table, if they have everything goes forward.
                // Reason for -1 is the id, id usually is not generated in models
                // but is generated in whatever storing mechanisam used.
                // But if ID is auto-generated inside Model, the above flag gets evaluated
                // And properly sets the count.
                if($useID == false){
                    $nDescribedTableCount = count($aDescribedTable)-1;
                    $nDescribedTableCount+=$nExtraCounter;
                }

                else if($useID == true){
                    $nDescribedTableCount = count($aDescribedTable);
                    $nDescribedTableCount-=$nExtraCounter;
                    
                }

                if($nDescribedTableCount == count($argArrayKeys)){
                    $tableMatch = true;
                }

                else if($nDescribedTableCount > count($argArrayKeys)){
                    echo "The table has more fields than was supplied";
                    $tableMatch = false;
                }

                else if($nDescribedTableCount < count($argArrayKeys)){
                    echo "The table has less fields than was supplied";
                    $tableMatch = false;
                }
                // If everything is okay, continue with the execution.
                if($tableMatch){

                    //If useID is set as false, remove ID from to-be query string
                        $aAllFieldsWithoutId = array();
                        // Iterate through described table, if $useId was specified as false, do not use ID in insertion,else use ID in insertion.
                        // $useId should be set as true if the user is using Model to increment Id.
                        foreach($aDescribedTable as $sRow){
                            if($useID == false){
                                if($aDescribedTable[0] == $sRow){
                                    continue;
                                }
                                else{
                                    array_push($aAllFieldsWithoutId, $sRow);
                                }
                            }
                            else{
                                
                                array_push($aAllFieldsWithoutId, $sRow);
                            }
                    
                        }
                        
                        // All keys from table E.G username, password, etc.
                        $sTableKeys = implode(',', $aAllFieldsWithoutId);

                        $aArguments = array();
                        
                        
                        // Used to remove the first instance of Model which by convetion should be the table name
                        // if the model is getting inserted.
                        $aSelfInstance = array();
                        foreach($selfInstance as $sI){
                            array_push($aSelfInstance, $sI);
                            break;
                        }
                        // Iterate through instance and check types so they can be stored apt.
                        foreach($selfInstance as $key => $param){
                            if($param == Util::getEnv('default_user_table') || $param == $aSelfInstance[0]){
                                continue;
                            }
                            
                            if(is_numeric($param)){
                                $param = (int)$param;
                                array_push($aArguments, $param);
                            }
                            else if(is_float($param)){
                                $param = (float)$param;
                                array_push($aArguments, $param);
                            }
                           
                            // Check if table entry is an object E.G Model user could have instance of "groups" class, and that instance gets stored as an object to table.
                            else if(is_object($param)){
                                foreach($param as $singularParam => $singularParamValue){
                                    if($singularParamValue == $tableName){
                                        continue;
                                    }
                                    else{
                                        array_push($aArguments, $singularParamValue);
                                    }
                                }
                            }
                            else{
                                if(is_array($param)){
                                    continue;
                                }
                                else{
                                    // if(!is_null($param)){
                                        array_push($aArguments, $param);
                                    // }
                                }
                            }
                        }
                        $aArgumentsForPrepared = array();

                        // Create prepared statemetns
                        for($i =0; $i<count($aAllFieldsWithoutId); $i++){
                            $arg = ":" . $aAllFieldsWithoutId[$i];
                            array_push($aArgumentsForPrepared, $arg);
                        }
                        $aExecuteArray = array();
                        
                        
                        $argsforPrepared = implode(',', $aArgumentsForPrepared);
                        
                        $sQuery = "INSERT INTO $tableName ($sTableKeys) VALUES ($argsforPrepared)";
                        
                        for($i = 0; $i <count($aAllFieldsWithoutId); $i++){
                            $aExecuteArray[$aAllFieldsWithoutId[$i]] = $aArguments[$i];     
                        }
                        
                        $oRecord = $databaseConnection->prepare($sQuery);
                        $oRecord->execute($aExecuteArray);
                            
                }
                
                else{
                    echo "Tables do not match, please Check your model.";
                }
                
            }
        }
    }

    /**
     * Function to fetch All from a table
     * @param string $tableToSelectFrom string, name of the table
     * 
     * @return array $aData array of all fetched data
     */

    public function select($tableToSelectFrom){
        $database = new Database();
        $databaseConnection = $database->connect();
        $query = $databaseConnection->query("SELECT * FROM $tableToSelectFrom");
        $aData = $query->fetch();
        return $aData;

    }

    /**
     * Function to fetch specific record
     * @param string $tableToSelectFrom name of the table
     * @param string $argument that we want to pick E.G id, name
     * @param string $argumentValue that we are supplying E.G where ID = $argumentValue
     * 
     * @return array $aResult array of fetched record
     */
    public function fetch($tableToSelectFrom, $argument, $argumentValue){
        
        // Array for fetched record
        $aResult = array();

        // Open Connection and connect to database
        $database = new Database();
        $databaseConnection = $database->connect();

        // Query that gets sent
        $query = $databaseConnection->prepare("SELECT * FROM $tableToSelectFrom WHERE $argument = :argumentValue");

        // Array of paramas
        $aExecuteArray = [
            'argumentValue' =>$argumentValue
        ];
        // Execute query
        if($query->execute($aExecuteArray)){
        
            while($oRow = $query->fetch(PDO::FETCH_ASSOC)){

                array_push($aResult, $oRow);
            }
            return $aResult;
        }
        else{
            echo "Error querying database, please check the supplied arguments.";
        }

    }
    
    /**
    * Method used to hash a given Password
    *
    * @return string password that was hashed with bcrypt, use check_password to login
    */
   public function hashPassword($password){
       return password_hash($password, PASSWORD_BCRYPT, array('cost'=>11));
   }
   /**
    * Method that checks whether a password corresponds to a hash that is saved in database
    * Uses passowrd_verify function to compare it by using password and seed.
    * @param string $password plain-text password that is sent from FORM
    * @param string $hash hashed $password
    * 
    * @return bool whether password exists (Did user supply correct pass)
    */
   public static function checkPassword($password, $hash){
    if(password_verify($password, $hash)){
        return true;
    }
    else{
        return false;
    }

   }
}



?>