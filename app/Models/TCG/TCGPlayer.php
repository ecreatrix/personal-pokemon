<?php

namespace App\Models\TCG;

/**
 * Class TCGPlayer
 *
 * @package Pokemon\Models
 */
class TCGPlayer extends Model {
    /**
     * @var Prices|null
     */
    private $prices;

    /**
     * @var string|null
     */
    private $updatedAt;

    /**
     * @var string
     */
    private $url;

    /**
     * @return Prices|null
     */
    public function getPrices():  ? Prices {
        return $this->prices;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt() :  ? string {
        return $this->updatedAt;
    }

    /**
     * @return string|null
     */
    public function getUrl() :  ? string {
        return $this->url;
    }

    /**
     * @param Prices|null $prices
     */
    public function setPrices(  ? Prices $prices ) {
        $this->prices = $prices;
    }

    /**
     * @param string|null $updatedAt
     */
    public function setUpdatedAt(  ? string $updatedAt ) {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(  ? string $url ) {
        $this->url = $url;
    }
}