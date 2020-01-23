<?php

namespace App\Entity\EntityCommon;

use App\Entity\EntityCommon\IEntity;

interface ICreate {
    static function create(Array $data): IEntity;
}
