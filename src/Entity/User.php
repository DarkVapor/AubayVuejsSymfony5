<?php

namespace App\Entity;

use App\Entity\EntityCommon\IValidate;

use App\Entity\EntityCommon\ICreate;

use App\Entity\EntityCommon\IEntity;

use App\Entity\EntityCommon\IPopulate;

use Doctrine\ORM\Mapping as ORM;

use App\Validator\PasswordValidator;
use DateTime;
use DateTimeZone;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements ICreate, IEntity,  IPopulate {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tokenDate;

    
    /**
     * 
     */
    private $repassword;


 
    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function getTokenDate(){
        return $this->tokenDate;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    
    }

    public function setRepassword(string $password): self {
        $this->repassword = $password;

        return $this;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    public function setToken($token ){
        $this->token = $token;
        return $this;
    }
    
    public function setTokenDate($date){
        $this->tokenDate = $date;
        return $this;
    }


    /**
     * Factory Method 
     * @param type $_user
     * @return \App\Entity\User
     */
    public static function create($_user): User {
        $user = new User();
        
        $user->name = $_user['name'];
        $user->email = $_user['email'];
        $user->password = PasswordValidator::Encryption($_user['password']);
        $user->repassword = $_user['repassword'];
        $user->token = '';
        $user->tokenDate = new DateTime('now');
        

        return $user;
    }

    /**
     * Population Method
     * @param array $data
     */
    public function populate(array $data){
        
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
            
        }
        if(isset($data['password']))
            $this->password = PasswordValidator::Encryption($data['password']);
     
    }

}
