<?php
namespace EmpFeed\App\Models;

Use EmpFeed\Core\Model;
    



class User extends Model{
    
    public $tableName = 'Users';
    
    public $email;

    public $password;

    public $createdAt; 

    public $isAdmin;

    protected $protected = [
        'isAdmin',
        'isTeamLeader',
        'isTeamOfficer'
    ];
    protected $fillable = [
        'email',
        'password',
        'createdAt'
    ];
    public function __construct(){
        $this->createdAt = date('d/m/Y h:i:s a', time());
        $this->password = $this->putHash();
        
    }  
    public function putHash(){
        return $this->hashPassword($this->password);
    }

}





?>