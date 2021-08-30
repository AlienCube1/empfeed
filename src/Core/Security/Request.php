<?php
namespace EmpFeed\Core\Security;


class Request{
    
    /**
     * @param $RequestType Request Method that was sent
     */

    protected $requestMethod;

    /**
     * @param $aRequestData array of all data that was sent.
     */
    protected $requestData = [];

    public function __construct(){
        $this->checkType();
        $this->getData();
    }

    /**
     * Check which method server sent.
     */
    public function checkType(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->requestMethod = 'POST';
        }
        else if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $this->requestMethod = 'GET';
        }
    }
    /**
     * Store all data inside array.
     */
    public function getData(){
        if($this->requestMethod = 'POST'){
            $aPostArray = array();
            $aPostArray = $_POST;
            foreach($aPostArray as $key=>$value){
                $this->requestData[$key] = $value;
                
            }
        }
    }
    /**
     * Return instance of self.
     */
    public function getRequestData(){
        return $this->requestData;
    }

    public function __destruct(){
        $this->requestData = null;
    }
    /**
     * Method that takes in any number of arguments and lets user filter what
     * data they want from POST
     * @param argv,arc
     * 
     * @return array @aFilterArray array of filtered data.
     */
    public function filter(){
        $aFilterArray = array();
        foreach(func_get_args() as $singleArg ){
            foreach($this->requestData as $data=>$value){
                if($singleArg == $data){
                    $aFilterArray[$data] = $value;
                }
            }
        }
        return $aFilterArray;
    }

}



?>