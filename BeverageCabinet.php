<?php

/**
 * Design a beverage cabinet that can hold a single type of beverage (For example: 33cl can of cola).
 * The cabinet consists of 3 shelves and each shelf can hold 20 cans of beverage.
 *
 * Only 1 can of beverage can be bought or placed at the same time.
 *
 * The door of the cabinet can be open or closed.
 *
 * The cabinet may be in a completely empty, partially filled, or fully loaded state.
 *
 * The closet should be able to control the free space and no more boxes can be added when it is full.
 */


class BeverageCabinet
{
    private const ACCEPTED_PROCESS = 1;
    private const ACCEPTED_BEVERAGE = ['NAME' => 'Coke', 'TYPE' => '33cl'];
    private const CAPACITY_STATUS = ['EMPTY', 'FULL', 'PARTIALLY'];
    private const DOOR_STATUS = ['CLOSE', 'OPEN'];

    private const SHELF_COUNT = 3;
    private const SHELF_CAPACITY = 20;
    private $doorStatus;
    private $capacityStatus;
    private $maxBeverageCountCapacity;
    private $beverage = [];

    public function __construct()
    {
        $this->doorStatus = $this::DOOR_STATUS['CLOSE'];
        $this->capacityStatus = $this::CAPACITY_STATUS['EMPTY'];
        $this->maxBeverageCountCapacity = $this::SHELF_COUNT * $this::SHELF_CAPACITY;
    }

    public function getBeverage(int $count)
    {
        if ($this->doorStatus !== $this::DOOR_STATUS['CLOSE']) {
            new \Exception('Please open the door!');
        }

        if ($this->getCapacityStatus() === $this::CAPACITY_STATUS['EMPTY']) {
            new \Exception('Cabinet is empty!');
        }

        if ($count !== $this::ACCEPTED_PROCESS) {
            new \Exception(sprintf('Cabinet process capacity is %d', $this::ACCEPTED_PROCESS));
        }

        array_pop($this->beverage);
        $this->updateCapacityStatus();

        return true;
    }

    public function addBeverage(IBeverage $beverage, int $count)
    {
        if ($this->doorStatus !== $this::DOOR_STATUS['CLOSE']) {
            new \Exception('Please open the door!');
        }

        if ($beverage->getType() !== $this::ACCEPTED_BEVERAGE['TYPE']) {
            new \Exception('Invalid beverage type!');
        }

        if ($beverage->getName() !== $this::ACCEPTED_BEVERAGE['NAME']) {
            new \Exception('Invalid beverage name!');
        }

        if ($this->getCapacityStatus() === $this::CAPACITY_STATUS['FULL']) {
            new \Exception('Cabinet capacity is full!');
        }

        if ($count !== $this::ACCEPTED_PROCESS) {
            new \Exception(sprintf('Cabinet process capacity is %d', $this::ACCEPTED_PROCESS));
        }

        array_push($this->beverage, $beverage);
        $this->updateCapacityStatus();

        return true;
    }

    private function updateCapacityStatus(): void
    {
        $this->capacityStatus = count($this->beverage);

        if (0 === $this->capacityStatus) {
            $this->capacityStatus = $this::CAPACITY_STATUS['EMPTY'];
        }

        if ($this->capacityStatus > 1 && $this->capacityStatus < $this->maxBeverageCountCapacity) {
            $this->capacityStatus = $this::CAPACITY_STATUS['PARTIALLY'];
        }

        if ($this->capacityStatus === $this->maxBeverageCountCapacity) {
            $this->capacityStatus = $this::CAPACITY_STATUS['FULL'];
        }
    }

    public function getCapacityStatus()
    {
        return $this->capacityStatus;
    }

    public function openDoor(): void
    {
        $this->doorStatus = $this::DOOR_STATUS['OPEN'];
    }

    public function closeDoor(): void
    {
        $this->doorStatus = $this::DOOR_STATUS['CLOSE'];
    }

    public function checkDoor()
    {
        return $this->doorStatus;
    }
}

interface IBeverage
{
    public function __construct($name, $type);

    public function getName(): string;

    public function getType(): string;
}

class Coke implements IBeverage
{
    private $name;
    private $type;

    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
