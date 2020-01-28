<?php 
namespace App\Validator;

use Exception;

class PasswordValidator implements Base\IValidator{ 
    /**
     * @var string 
     */
    private $data;
    /**
     * @var string
     */
    private $hash;

    public function encryption($password){
        $options = [
            'cost' => 12,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
    
    public function setData($data){
        if(isset($data['data']) && isset($data['hash'])){
            $this->data = $data['data'];
            $this->hash = $data['hash'];
            return $this;
        }
        throw new Exception('Unable to retreive ["data"] and ["hash"] key in array, please add those keys and values.');
   
    }

    public function valid():bool{
        
        if($this->data === null){
            throw new Exception('Unable to validate empty data, missed setData.');
        }

        $return = false;
        if( password_verify($this->data, $this->hash) && $this->data !=="" && $this->hash !==""){
            $return = true;
        }
        return $return;
    }

}