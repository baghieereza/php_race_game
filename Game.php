<?php

require 'vendor/autoload.php';

use cli\Colors;
use cli\Table;
use cli\table\Ascii;
use function cli\line;

class Vehicle
{
    private $id;
    private $name;
    private $maxSpeed;
    private $unit;

    public function __construct($id, $name, $maxSpeed, $unit)
    {
        $this->id = $id;
        $this->name = $name;
        $this->maxSpeed = $maxSpeed;
        $this->unit = $unit;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMaxSpeed()
    {
        return $this->maxSpeed;
    }
    public function getUnit()
    {
        return $this->unit;
    }

    public function getSpeedInKmh()
    {
        return $this->unit === 'Km/h' ? $this->maxSpeed : $this->maxSpeed * 1.852;
    }
}
