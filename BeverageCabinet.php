<?php

/**
 * V1
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

/**
 * V2
 * Change the design so that the cabinet will receive two types of drinks, 33cl and 50cl.
 * Each shelf can hold 20 pieces 33cl or 10 pieces 50cl.
 * It can also take 33cl and 50cl mixed provided that it does not exceed the shelf capacity.
 * (For example, 5 units of 50cl and 10 units of 33cl fit together in a single rack.)
 */

class BeverageCabinet
{
    private const ACCEPTED_PROCESS = 1;
    private const ACCEPTED_BEVERAGE_TYPE = ['33cl', '50cl'];
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
        $this->doorStatus = self::DOOR_STATUS['CLOSE'];
        $this->capacityStatus = self::CAPACITY_STATUS['EMPTY'];
        $this->maxBeverageCountCapacity = self::SHELF_COUNT * self::SHELF_CAPACITY;
    }

    public function getBeverage(int $count)
    {
        if ($this->doorStatus !== self::DOOR_STATUS['CLOSE']) {
            return 'Please open the door!';
        }

        if ($this->getCapacityStatus() === self::CAPACITY_STATUS['EMPTY']) {
            return 'Cabinet is empty!';
        }

        if ($count !== self::ACCEPTED_PROCESS) {
            return sprintf('Cabinet process capacity is %d', self::ACCEPTED_PROCESS);
        }

        $getBeverage = array_pop($this->beverage);
        $this->updateCapacityStatus();

        return $getBeverage;
    }

    public function addBeverage(IBeverage $beverage, int $count)
    {
        if ($this->doorStatus !== self::DOOR_STATUS['CLOSE']) {
            return 'Please open the door!';
        }

        if (!in_array($beverage->getType(), self::ACCEPTED_BEVERAGE_TYPE)) {
            return 'Invalid beverage type!';
        }

        if ($this->getCapacityStatus() === self::CAPACITY_STATUS['FULL']) {
            return 'Cabinet capacity is full!';
        }

        if ($count !== self::ACCEPTED_PROCESS) {
            return sprintf('Cabinet process capacity is %d', self::ACCEPTED_PROCESS);
        }

        array_push($this->beverage, $beverage);
        $this->updateCapacityStatus();

        return true;
    }

    private function updateCapacityStatus(): void
    {
        $this->capacityStatus = count($this->beverage);

        if (0 === $this->capacityStatus) {
            $this->capacityStatus = self::CAPACITY_STATUS['EMPTY'];
        }

        if ($this->capacityStatus > 1 && $this->capacityStatus < $this->maxBeverageCountCapacity) {
            $this->capacityStatus = self::CAPACITY_STATUS['PARTIALLY'];
        }

        if ($this->capacityStatus === $this->maxBeverageCountCapacity) {
            $this->capacityStatus = self::CAPACITY_STATUS['FULL'];
        }
    }

    public function getCapacityStatus()
    {
        return $this->capacityStatus;
    }

    public function openDoor(): void
    {
        $this->doorStatus = self::DOOR_STATUS['OPEN'];
    }

    public function closeDoor(): void
    {
        $this->doorStatus = self::DOOR_STATUS['CLOSE'];
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

class Beverage implements IBeverage {

    protected $name;
    protected $type;

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

    public function __toString() {
        return $this->type . ' ' . $this->name;
    }
}

$coke33cl = new Beverage('Coke', '33cl');
$coke50cl = new Beverage('Coke', '50cl');



