<?php
namespace EmpFeed\Core;
Use EmpFeed\Core\Security\Request;

/**
 * Controller class which has all the important elements that gets inherited by other Controllers
 * 
 */
class Controller{
    
    protected $request;

    public $requestData;
    /**
     * Constructor
     */
    public function __construct(){
        $this->request = new Request;
        $this->requestData = $this->request->getRequestData();
    }
    public function returnSelfInstance(){
        return $this;
    }
    // Method that redirects.
    public function redirect($pageToRedirectTo){
        header('location:'. $pageToRedirectTo);
    }
    

}




?>