<?php

require_once '../vendor/autoload.php';

Use EmpFeed\Core\Model;
Use EmpFeed\App\Models\User;
Use EmpFeed\App\Http\Controllers\userController;
Use EmpFeed\Core\Route;
Use EmpFeed\Core\View;
Use EmpFeed\Core\Authentication\Auth;
$userController = new userController();

//error_reporting(0);

View::Make('navbar.html');

Route::get('home');

Route::middleware('bla');

Route::get('login');
Route::post('login', $userController,"login");

Route::get('register');

Route::post('register', $userController, 'storeUser');

Route::get('logout', $userController, 'logout');

?>