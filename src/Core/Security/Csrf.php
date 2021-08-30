<?php

namespace EmpFeed\Core\Security;

Use EmpFeed\Core\Security\Session;

class Csrf{
    public $token;
    public $session;

    public function __construct(){
        $this->token = bin2hex(random_bytes(32));
        $this->session = new Session();
        $this->session->setSessionVariable('csrf', $this->token);
    }
    public function verifyCsrf($token){
        if(!empty($token)){
            if(hash_equals($_SESSION['csrf'])==$token){
                return true;
            }
            else{
                echo "CSRF token mismatch";
            }
        }
    }
}




?>