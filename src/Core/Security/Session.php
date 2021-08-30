<?php

namespace EmpFeed\Core\Security;
session_start();

class Session{
    public function __construct(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }
    
    public function setSessionVariable($nameOfVariable, $valueOfVariable){
        $_SESSION[$nameOfVariable] = $valueOfVariable;
    }
    public function getSessionVariable($nameOfVariable){
        if(isset($_SESSION[$nameOfVariable])){
            return $_SESSION[$nameOfVariable];
        }
    }
    public function removeSessionVariable($nameOfVariable){
        $_SESSION[$nameOfVariable] = null;
    }
    public function destroySession(){
        $_SESSION = null;
        session_destroy();
        session_abort();
    }


}


?>