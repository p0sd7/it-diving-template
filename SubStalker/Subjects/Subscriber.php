<?php

namespace SubStalker\Subjects;

class Subscriber extends User
{
    private int $sex; //1 => female; 2 => male;
    private bool $user_privacy;

    public function __construct(int $id, string $name, int $sex, bool $user_privacy)
    {
        parent::__construct($id, $name);

        $this->sex = $sex;
        $this->user_privacy = $user_privacy;
    }

    public function isFemale(): bool
    {
        return $this->sex === 1;
    }

    public function isMale(): bool
    {
        return $this->sex === 2;
    }

    public function privacyStatus(): bool
    {
        return $this->user_privacy;
    }
}