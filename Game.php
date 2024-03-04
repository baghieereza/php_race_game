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

class Game
{
    private $vehicles;

    public function __construct(array $vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function displayVehicles()
    {
        $table = new Table();
        $table->setHeaders(['id', 'name', 'maxSpeed', 'unit']);

        $rows = [];
        foreach ($this->vehicles as $vehicle) {
            $rows[] = [$vehicle->getId(), $vehicle->getName(), $vehicle->getMaxSpeed(), $vehicle->getUnit()];
        }

        $table->setRows($rows);
        $table->setRenderer(new Ascii([3, 15, 8]));
        $table->display();
    }

    public function getSelectedVehicle($player)
    {
        $vehicleIndex = (int) readline("Vehicle for player $player: ");
        if ($vehicleIndex < 0 || $vehicleIndex >= count($this->vehicles)) {
            echo Colors::colorize('%C%5 Vehicle must be between 0 - ' . (count($this->vehicles) - 1) . ' %C%5  %n') . "\n";
            return $this->getSelectedVehicle($player);
        }

        return $this->vehicles[$vehicleIndex];
    }

    public function getDistance()
    {
        $distance = (int) readline('Please enter distance (km): ');
        if ($distance <= 0) {
            echo Colors::colorize('%C%5 Distance must be greater than 0 %C%5 %n') . "\n";
            return $this->getDistance();
        }
        return $distance;
    }

    public function determineWinner($distance, $vehicleOne, $vehicleTwo)
    {
        $winner = $vehicleOne->getSpeedInKmh() > $vehicleTwo->getSpeedInKmh() ? 'Player one is winner' : 'Player two is winner';
        echo Colors::colorize('%C%5' . $winner . '%C%5  %n') . "\n";

        line('Player one will be there after ' . $this->calculateWinner($vehicleOne->getSpeedInKmh(), $distance) . ' hour');
        line('Player two will be there after ' . $this->calculateWinner($vehicleTwo->getSpeedInKmh(), $distance) . ' hour');
    }

    private function calculateWinner($speed, $distance)
    {
        return round($distance / $speed, 2);
    }
}

$str = file_get_contents('./vehicles.json');
$vehiclesList = json_decode($str, true);
$vehicles = [];

foreach ($vehiclesList as $index => $value) {
    $vehicles[] = new Vehicle("[$index]", $value['name'], $value['maxSpeed'], $value['unit']);
}

$game = new Game($vehicles);
$game->displayVehicles();

$playerOneVehicle = $game->getSelectedVehicle('one');
$playerTwoVehicle = $game->getSelectedVehicle('two');
$distance = $game->getDistance();
$game->determineWinner($distance, $playerOneVehicle, $playerTwoVehicle);