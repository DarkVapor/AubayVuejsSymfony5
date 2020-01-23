<?php

namespace App\Entity;

use App\Entity\EntityCommon\IValidate;

use App\Entity\EntityCommon\ICreate;

use App\Entity\EntityCommon\IEntity;

use App\Entity\EntityCommon\IPopulate;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements IValidate, ICreate, IEntity,  IPopulate{

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

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'repassword' => $this->password,
        ];
    }

    /**
     * Validation Method
     */
    public static function validate($_user): bool {
        return true;
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
        $user->password = $_user['password'];
        

        return $user;
    }
    /**
     * Population Method
     * @param array $data
     */
    public function populate(array $data){
        if($this->validate($data))
        foreach($data as $key => $value){
            if(isset($this->$key)){
                $this->$key = $value;
            }
        }
    }

}
