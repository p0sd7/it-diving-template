<?php

namespace SubStalker\Subjects;
use SubStalker\Config;


class Club extends User
{
    public function __construct(int $id, string $name)
    {
        parent::__construct($id, $name);
    }
}