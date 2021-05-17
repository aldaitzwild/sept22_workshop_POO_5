<?php

namespace App;

class Level
{
    static public function calculate(int $experience): int 
    {
        return ceil($experience / 1000);
    }
}