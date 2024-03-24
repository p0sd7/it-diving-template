<?php

namespace SubStalker\Subjects;

class Subscriber extends User
{
    private int $sex; //0 => female; 1 => male;

    public function __construct(int $id, string $name, int $sex)
    {
        parent::__construct($id, $name);

        $this->sex = $sex;
    }

    public function isFemale(): bool
    {
        return $this->sex === 0;
    }

    public function isMale(): bool
    {
        return $this->sex === 1;
    }
}