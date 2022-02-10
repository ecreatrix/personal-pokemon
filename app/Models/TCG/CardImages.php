<?php

namespace App\Models\TCG;

/**
 * Class CardImages
 *
 * @package Pokemon\Models
 */
class CardImages extends Model {
    /**
     * @var string|null
     */
    private $large;

    /**
     * @var string|null
     */
    private $small;

    /**
     * @return string|null
     */
    public function getLarge():  ? string {
        return $this->large;
    }

    /**
     * @return string|null
     */
    public function getSmall() :  ? string {
        return $this->small;
    }

    /**
     * @param string|null $large
     */
    public function setLarge(  ? string $large ) {
        $this->large = $large;
    }

    /**
     * @param string|null $small
     */
    public function setSmall(  ? string $small ) {
        $this->small = $small;
    }
}