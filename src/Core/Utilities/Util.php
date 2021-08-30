<?php
namespace EmpFeed\Core\Utilities;

use EmpFeed\Core\Utilities\DotEnv;
class Util{
    /**
     * Method that creates an instance of a DotEnv class (Which is not static)
     * 
     * @param string $argumentToGet name of the argument from .env
     * 
     * @return instance of a method getenv which returns a string 
     */
    public static function getEnv($argumentToGet){
        $dotEnv = new DotEnv(dirname(__DIR__,3)."/.env");
        $dotEnv->load();
        
        return getenv($argumentToGet);
        
        
    }
}


?>