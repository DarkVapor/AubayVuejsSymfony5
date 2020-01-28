<?php

namespace App\Validator;

use Exception;

class UserValidator implements Base\IValidator
{

    /**
     * Errors to return 
     * @var array
     */
    private $errors;

    /**
     * Data to validate
     * @var array
     */
    private $data;

    /**
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {

        $this->_init($data);
        $this->_name();
        $this->_email();

        /**
         * Check passwords only when new user or when update with data into passwords input
         */
        if (isset($data['id']) && (isset($data['password']) || isset($data['repassword'])) || !isset($data['id'])) {
            $this->_password();
        }

        return $this;
    }
    /**
     * 
     * @return void
     */
    private function _email()
    {
        if ($this->data['email'] === "") {
            $this->errors['valid'] = false;
            $this->errors['email'] = [
                "message" => "Email cannot be empty",
                "notvalid" => true
            ];
        }
    }
    /**
     *
     * @return void
     */
    private function _name()
    {
        if ($this->data['name'] === "") {
            $this->errors['valid'] = false;
            $this->errors['name'] = [
                "message" => "Name cannot be empty",
                "notvalid" => true
            ];
        }
    }
    /**
     *
     * @return void
     */
    private function _password()
    {      
        
        if (isset($this->data['password']) && isset($this->data['repassword'])) {

            if ($this->data['password'] !== $this->data['repassword']) {
                $this->errors['valid'] = false;
                $this->errors['password'] = $this->errors['repassword'] = [
                    "message" => "Passwords must be the same",
                    "notvalid" => true
                ];
            }

            if ($this->data['password'] === "" || $this->data['repassword'] === "") {
                $this->errors['valid'] = false;
                $this->errors['password'] = $this->errors['repassword'] = [
                    "message" => "Passwords cannot be empty",
                    "notvalid" => true
                ];
            }

        } else {

            $this->errors['valid'] = false;
            $this->errors['password'] = $this->errors['repassword'] = [
                "message" => "Passwords cannot be empty",
                "notvalid" => true
            ];

        }


    }
    /**
     *
     * @param array $data
     * @return void
     */
    private function _init($data)
    {

        $this->data = $data;
        $this->errors = [];

        $this->errors['valid'] = true;

        $this->errors['name'] = [
            "message" => "",
            "notvalid" => false
        ];

        $this->errors['email'] = [
            "message" => "",
            "notvalid" => false
        ];

        
    }
    /**
     * 
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    /**
     *
     * @return boolean
     */
    public function valid(): bool
    {

        if ($this->data === null) {
            throw new Exception('Unable to validate empty data, missed setData.');
        }

        return $this->errors['valid'];
    }
}
