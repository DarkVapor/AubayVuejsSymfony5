<?php

namespace App\Entity\EntityCommon;


interface IEntity{
    function getId(): ?int;
    function toArray();
}
