<?php

namespace SubStalker\Subjects;

class Owner extends User
{
    public function __construct(int $id, string $name)
    {
        parent::__construct($id, $name);
    }
}