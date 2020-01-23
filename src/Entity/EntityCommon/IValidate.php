<?php

namespace App\Entity\EntityCommon;


interface IValidate {
    static function validate(Array $data): bool;
}
