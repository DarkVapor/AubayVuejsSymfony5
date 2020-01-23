<?php

namespace App\Entity\EntityCommon;

use App\Entity\EntityCommon\IEntity;

interface IPopulate {
    function populate(Array $data);
}
