<?php

declare(strict_types=1);

namespace Xander\PizzaOrder\Model;

class Pizza {
    public string $size;
    public array $add_ons;
    public int $price;

    public function __construct(string $size, array $add_ons = []) {
        $this->size = $size;
        $this->add_ons = $add_ons;
        $this->price = $this->calculatePrice();
    }

    public static array $sizeOptions = [
        'small' => 'small',
        'medium' => 'medium',
        'large' => 'large',
    ];
    public static array $addOnOptions = [
        'pepperoni' => 'pepperoni',
        'extra_cheese' => 'extra_cheese',
    ];

    private function pizzaCost($size): int
    {
        $cost = 0;
        switch ($size) {
            case static::$sizeOptions['small']:
                $cost = 15;
                break;
            case static::$sizeOptions['medium']:
                $cost = 22;
                break;
            case static::$sizeOptions['large']:
                $cost = 30;
                break;
        }

        return $cost;
    }

    private function addOnCost($add_on, $size): int
    {
        $cost = 0;

        if ($add_on === static::$addOnOptions['pepperoni']) {
            switch ($size) {
                case static::$sizeOptions['small']:
                    $cost = 3;
                    break;
                case static::$sizeOptions['medium']:
                    $cost = 5;
                    break;
            }
        }
        else if ($add_on === static::$addOnOptions['extra_cheese']) {
            $cost = 6;
        }

        return $cost;
    }

    public function calculatePrice(): int
    {
        $price = $this->pizzaCost($this->size);
        foreach ($this->add_ons as $add_on) {
            $price += $this->addOnCost($add_on, $this->size);
        }

        return $price;
    }
}