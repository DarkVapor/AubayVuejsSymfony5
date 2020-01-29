<?php 
namespace App\Validator;

use Exception;
use App\Repository\UserRepository;

class LoginValidator implements Base\IValidator{


    
    /**
     * @var string 
     */
    private $email;
    /**
     * @var string
     */
    private $password;
    /**
     * @var PasswordValidator
     */
    private $pv;
    /**
     * @var App\Repository\UserRepository
     */
    private $ur;

    public function __construct(UserRepository $ur)
    {
        $this->ur = $ur;

    }
    
    public function setData($data){
        if(isset($data['email']) && isset($data['password'])){
            $this->data = $data;
        }else{
            throw new Exception('Access Denied');
        }
        return $this;
    }

    public function valid():bool{
        $user = $this->ur->findOneBy(["email" => $this->data["email"]]);
        if($user instanceof \App\Entity\User){
            $password = $user->getPassword();
            if(password_verify($this->data['password'], $password)){
                return true;
            }
        }
        return false;
     
    }

}