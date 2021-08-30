<?php
include_once('Controller.php');

class Session{

    public function getSessionVariable($sessionVariable){
        session_start();
        return $_SESSION[$sessionVariable];
        session_abort();
    }

}


?>