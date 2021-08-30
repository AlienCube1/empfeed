<?php
namespace EmpFeed\Core\Authentication;
use EmpFeed\Core\Model;
use EmpFeed\Core\Utilities\Util;
Use EmpFeed\Core\Security\Session;
class Auth{

    public static $aErrors = [];

    /**
     * Function that is used to attempt to login user
     * @param array $credentials array of credentials
     * $credentials should be the same as stated in .env
     * 
     * So if default_user_query is username, $credentials should have username in them at first place.
     * 
     * 
     */
    public static function attempt($credentials){
        
        if(!empty($credentials)){
        $Model = new Model;
        $session = new Session;
        $tableName = Util::getEnv('default_user_table');

        $userAuth = Util::getEnv('default_user_auth_name');
        
        $userId = Util::getEnv('default_user_id');
        $aUser = $Model->fetch($tableName,$userAuth, $credentials[$userAuth]);
        if(!$aUser){
            array_push(self::$aErrors, false);
            array_push(self::$aErrors, "User not found!");
            return self::$aErrors;
        }
        else{
            if($Model->checkPassword($credentials['password'],$aUser[0]['password'])){
                    $session->setSessionVariable('loggedin', true);
                    $session->setSessionVariable('userId', $aUser[0][$userId]);
                    array_push(self::$aErrors, true);
                    return self::$aErrors;
            }
            else{
                array_push(self::$aErrors, false);
                array_push(self::$aErrors, "Wrong password!");
                return self::$aErrors;
                }
            }
        }
    }
    /**
     * Method used to deauth users
     * 
     * @return bool return success.
     */


    public static function logout(){
        $session = new Session;
        $session->removeSessionVariable('loggedin');
        $session->removeSessionVariable('userId');
        echo "called";
        return true;
    }
    
}


?>