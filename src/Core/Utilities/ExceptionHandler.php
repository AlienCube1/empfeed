<?php

namespace EmpFeed\Core\Utilities;


class ExceptionHandler {

    public function __construct(){
        @set_exception_handler($this->throwNewException());
    }
    public function throwNewException($severity, $message, $filename, $lineno){
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
}



?>