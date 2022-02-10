<?php

namespace App\Models\TCG;

/**
 * Class Attack
 *
 * @package Pokemon\Models
 */
class Attack extends Model {
    /**
     * @var int
     */
    private $convertedEnergyCost;

    /**
     * @var array
     */
    private $cost;

    /**
     * @var string|null
     */
    private $damage;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $text;

    /**
     * @return int
     */
    public function getConvertedEnergyCost(): int {
        return $this->convertedEnergyCost;
    }

    /**
     * @return array
     */
    public function getCost(): array
    {
        return $this->cost;
    }

    /**
     * @return string|null
     */
    public function getDamage():  ? string {
        return $this->damage;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getText():  ? string {
        return $this->text;
    }

    /**
     * @param int $convertedEnergyCost
     */
    public function setConvertedEnergyCost( int $convertedEnergyCost ) {
        $this->convertedEnergyCost = $convertedEnergyCost;
    }

    /**
     * @param array $cost
     */
    public function setCost( array $cost ) {
        $this->cost = $cost;
    }

    /**
     * @param string|null $damage
     */
    public function setDamage(  ? string $damage ) {
        $this->damage = $damage;
    }

    /**
     * @param string $name
     */
    public function setName( string $name ) {
        $this->name = $name;
    }

    /**
     * @param string|null $text
     */
    public function setText(  ? string $text ) {
        $this->text = $text;
    }
}