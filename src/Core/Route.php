<?php
namespace EmpFeed\Core;

use EmpFeed\Core\Utilities\Util;
use EmpFeed\Core\Security\Session;
/**
 * Route class that gets used for routing, methods are static so that a new instance of a class doesnt have to be created
 * 
 * 
 */
class Route{
    protected static $currentRoute = "";
    protected static $newRoute = "";
    protected static $routeList = array();
    protected static $Controller = "";
    protected static $Method = "";
    public function __destruct(){
        self::$routeList = null;
    }
    public static function checkRoute($desiredRoute){
        foreach(self::$routeList as $route){
            if($route == $desiredRoute){
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    return true;
                }
            }
        }
    }

    public static function addRoute($RouteName){
        array_Push(self::$routeList, $RouteName);    
        
        
    }
    public static function returnRoutes(){
        return self::$routeList;
    }
    /**
     * Function splits string 
     * Function takes in a param of requestPage, the link is then read from the URL, stripped of .html and returned as a string
     * @param $requestPage String that needs to be strpied of .html / or other links
     * 
     * @return $stripped_string a string that doesnt contain .extension anymore
     */
    public static function split_string($requestPage){
        $arr = str_split($requestPage);
        $newArr = array();
        foreach($arr as $char){
            if($char != '.'){
                array_push($newArr, $char);
            }
            else{
                break;
            }
        }
        $stripped_string = implode('', $newArr);
        return $stripped_string;
    }
    /**
     * Function that is used to render views, takes in a single param that is then striped and checked if the current url matches that stripped
     * If params match, requested view gets rendered
     * @param $requestPage -> Page that is requested to be rendered
     * @param $function -> optional, function that gets called if a view doesnt need to be rendered(Function can also be called and a view can be renedered in the same time).
     * @return void -> although it can be changed to return instance of a view, but then it must be echoed in main.
     */
    public static function get($requestPage, $Controller="", $Method=""){
        
        $requestLink = self::split_string($requestPage);
        $Current_url = basename($_SERVER['REQUEST_URI']);
        
        
        if($Current_url == $requestPage){
            
            if($_SERVER['REQUEST_METHOD'] == 'GET' && $requestLink == $Current_url){
                if(empty($Method)){
                        self::$currentRoute = $Current_url;
                        echo View::Make($requestPage  . '.html');
                        exit();
                }
                else{
                    //// If get isnt a page name, call a function instead
                    echo $Controller->$Method();
                    //$Controller->{$func}();
                }
            }
        
        }
        */
    }
    
    /**
     * Function that is used to process form data, the data must be sent with POST
     * 
     * Function takes two params, one of the requestPage, second is the method of a controller which is then echoed, dumped, or parsed to JSON
     * 
     * @param string $requestPage page that is requested to get rendered 
     * 
     * @param object $Controller controller that is used to show data, any controller can be used, and any data type can be shown, either by echo or parsed to JSON 
     * 
     * @return void -> although it can be changed to return data that was parsed by controller, but then it must be echoed in main.
     * 
     */
    public static function post($requestPage, $Controller, $Method){
        
            
            self::addRoute($requestPage);
            if(self::checkRoute($requestPage)){
                if(!empty($Controller)){
                    self::$Controller = $Controller;
                    self::$Method = $Method;
                    
                    echo $Controller->$Method();
                }
                
            }
    }
    
    public static function middleware($page, $function = ""){
        
        if(basename($_SERVER['REQUEST_URI']) == $page){
            
            $session = new Session();
            $sessionId = $session->getSessionVariable(Util::getEnv('default_user_id'));
            if(isset($sessionId)){
                self::get($page);
            }
            else{
                echo "<br>Unauthorized";
            }
        }
    }  

}


?>