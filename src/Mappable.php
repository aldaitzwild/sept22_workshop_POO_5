<?php

namespace App;

interface Mappable
{
    public function getX();
    public function getY();
    public function setX(int $x);
    public function setY(int $y);
    public function getImage();
    public function setImage(string $image);
}