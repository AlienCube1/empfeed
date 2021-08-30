<?php
require_once '../vendor/autoload.php';

Use EmpFeed\App\Http\Controllers\userController;

$userController = new userController;

echo json_encode($userController->login());


?>