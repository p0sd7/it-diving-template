<?php

namespace SubStalker\Subjects;


class Club extends User
{
    public function __construct(int $id, string $name)
    {
        parent::__construct($id, $name);
    }
}