<?php

namespace App;

abstract class Tile implements Mappable
{
    private int $x;
    private int $y;
    protected string $image = "";
    protected bool $crossable = true;
    

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Get the value of x
     */ 
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set the value of x
     *
     * @return  self
     */ 
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get the value of y
     */ 
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set the value of y
     *
     * @return  self
     */ 
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return 'assets/images/' . $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of crossable
     */ 
    public function isCrossable()
    {
        return $this->crossable;
    }

    /**
     * Set the value of crossable
     *
     * @return  self
     */ 
    public function setCrossable($crossable)
    {
        $this->crossable = $crossable;

        return $this;
    }
}