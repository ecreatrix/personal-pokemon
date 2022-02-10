<?php

namespace App\Models\TCG;

/**
 * Class Set
 *
 * @package Pokemon\Models
 */
class Set extends Model {
    /**
     * @var string
     */
    private $id;

    /**
     * @var SetImages|null
     */
    private $images;

    /**
     * @var Legalities|null
     */
    private $legalities;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $printedTotal;

    /**
     * @var string|null
     */
    private $ptcgoCode;

    /**
     * @var string|null
     */
    private $releaseDate;

    /**
     * @var string
     */
    private $series;

    /**
     * @var int
     */
    private $total;

    /**
     * @var string|null
     */
    private $updatedAt;

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @return SetImages|null
     */
    public function getImages():  ? SetImages {
        return $this->images;
    }

    /**
     * @return Legalities|null
     */
    public function getLegalities() :  ? Legalities {
        return $this->legalities;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPrintedTotal(): int {
        return $this->printedTotal;
    }

    /**
     * @return string|null
     */
    public function getPtcgoCode():  ? string {
        return $this->ptcgoCode;
    }

    /**
     * @return string|null
     */
    public function getReleaseDate() :  ? string {
        return $this->releaseDate;
    }

    /**
     * @return string
     */
    public function getSeries() : string {
        return $this->series;
    }

    /**
     * @return int
     */
    public function getTotal(): int {
        return $this->total;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt():  ? string {
        return $this->updatedAt;
    }

    /**
     * @param string $id
     */
    public function setId( string $id ) {
        $this->id = $id;
    }

    /**
     * @param SetImages|null $images
     */
    public function setImages(  ? SetImages $images ) {
        $this->images = $images;
    }

    /**
     * @param Legalities|null $legalities
     */
    public function setLegalities(  ? Legalities $legalities ) {
        $this->legalities = $legalities;
    }

    /**
     * @param string $name
     */
    public function setName( string $name ) {
        $this->name = $name;
    }

    /**
     * @param int $printedTotal
     */
    public function setPrintedTotal( int $printedTotal ) {
        $this->printedTotal = $printedTotal;
    }

    /**
     * @param string|null $ptcgoCode
     */
    public function setPtcgoCode(  ? string $ptcgoCode ) {
        $this->ptcgoCode = $ptcgoCode;
    }

    /**
     * @param string|null $releaseDate
     */
    public function setReleaseDate(  ? string $releaseDate ) {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @param string $series
     */
    public function setSeries( string $series ) {
        $this->series = $series;
    }

    /**
     * @param int $total
     */
    public function setTotal( int $total ) {
        $this->total = $total;
    }

    /**
     * @param string|null $updatedAt
     */
    public function setUpdatedAt(  ? string $updatedAt ) {
        $this->updatedAt = $updatedAt;
    }
}