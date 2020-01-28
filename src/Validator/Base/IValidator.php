<?php
namespace App\Validator\Base; 

interface IValidator{
    function valid(): bool; 
    function setData(array $data);
}