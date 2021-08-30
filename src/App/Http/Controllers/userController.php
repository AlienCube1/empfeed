<?php
namespace EmpFeed\App\Http\Controllers;
Use EmpFeed\Core\Controller;
Use EmpFeed\Core\Security\Request;
Use EmpFeed\App\Models\User;
Use EmpFeed\Core\Authentication\Auth;
use EmpFeed\Core\Security\Csrf;
class userController extends Controller{

    public function storeUser(){
        $user = new User();
        $user->constructChild($this->request->filter('email', 'password', 'csrf'));
        $user->isAdmin = 1;
        $user->password = $user->putHash();
        $password = $this->request->filter('password')['password'];
        $repeatPassword = $this->request->filter('repeatPassword')['repeatPassword'];
        if($password == $repeatPassword){
            if(strlen($password) < 8){
                echo "Password must be atleast 8 chars long!";
            }
            else{
                $user->save($user);
            }

        }
        else{
            //echo "Passwords do not match";
        }
    }
    /**
     * Login user
     * $Credentials gets filtered from post data
     * $csrf gets read from $_SESSION
     * 
     */
    public function login(){
        $aReturnArray = [];
        $credentials = $this->request->filter('email','password', 'csrf');
        
        if(!empty($credentials)){
            $csrf = new Csrf();
            
                if(Auth::attempt($credentials)[0] == true){
                    $this->redirect('home');
                    array_push($aReturnArray, 'Logged in sucessfuly!');
                    
                }
                else{
                    // Salji na end-point
                    array_push($aReturnArray, 'Wrong username or password');
                    //return Auth::attempt($credentials);
                }
            // }     
        }
        else {
            array_push($aReturnArray,'All fields must be filled.');
        }
        return $aReturnArray;
    }
    public function logout(){
        echo "A";
        if($Current_url = basename($_SERVER['REQUEST_URI']) == 'logout'){
            
            if(Auth::logout()){
                $this->redirect('home');
            }
    }
    }
    public function sendToEndpoint(){
    
    }

}

?>