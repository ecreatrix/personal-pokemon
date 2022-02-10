<?php

namespace App\Models\TCG;

/**
 * Class Card
 *
 * @package Pokemon\Models
 */
class Card extends Model {
    /**
     * @var array|null
     */
    private $abilities;

    /**
     * @var AncientTrait|null
     */
    private $ancientTrait;

    /**
     * @var string|null
     */
    private $artist;

    /**
     * @var array|null
     */
    private $attacks;

    /**
     * @var CardMarket|null
     */
    private $cardmarket;

    /**
     * @var int|null
     */
    private $convertedRetreatCost;

    /**
     * @var string|null
     */
    private $evolvesFrom;

    /**
     * @var array|null
     */
    private $evolvesTo;

    /**
     * @var string|null
     */
    private $flavorText;

    /**
     * @var string|null
     */
    private $hp;

    /**
     * @var string
     */
    private $id;

    /**
     * @var CardImages|null
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
     * @var array|null
     */
    private $nationalPokedexNumbers;

    /**
     * @var string|null
     */
    private $number;

    /**
     * @var string|null
     */
    private $rarity;

    /**
     * @var array|null
     */
    private $resistances;

    /**
     * @var array|null
     */
    private $retreatCost;

    /**
     * @var array|null
     */
    private $rules;

    /**
     * @var Set
     */
    private $set;

    /**
     * @var array|null
     */
    private $subtypes;

    /**
     * @var string|null
     */
    private $supertype;

    /**
     * @var TCGPlayer|null
     */
    private $tcgplayer;

    /**
     * @var array|null
     */
    private $types;

    /**
     * @var array|null
     */
    private $weaknesses;

    /**
     * @return array|null
     */
    public function getAbilities():  ? array
    {
        return $this->abilities;
    }

    /**
     * @return AncientTrait|null
     */
    public function getAncientTrait() :  ? AncientTrait {
        return $this->ancientTrait;
    }

    /**
     * @return string|null
     */
    public function getArtist() :  ? string {
        return $this->artist;
    }

    /**
     * @return array|null
     */
    public function getAttacks() :  ? array
    {
        return $this->attacks;
    }

    /**
     * @return CardMarket|null
     */
    public function getCardmarket() :  ? CardMarket {
        return $this->cardmarket;
    }

    /**
     * @return int|null
     */
    public function getConvertedRetreatCost() :  ? int {
        return $this->convertedRetreatCost;
    }

    /**
     * @return string|null
     */
    public function getEvolvesFrom() :  ? string {
        return $this->evolvesFrom;
    }

    /**
     * @return array|null
     */
    public function getEvolvesTo() :  ? array
    {
        return $this->evolvesTo;
    }

    /**
     * @return string|null
     */
    public function getFlavorText() :  ? string {
        return $this->flavorText;
    }

    /**
     * @return string|null
     */
    public function getHp() :  ? string {
        return $this->hp;
    }

    /**
     * @return string
     */
    public function getId() : string {
        return $this->id;
    }

    /**
     * @return CardImages|null
     */
    public function getImages():  ? CardImages {
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
     * @return array|null
     */
    public function getNationalPokedexNumbers():  ? array
    {
        return $this->nationalPokedexNumbers;
    }

    /**
     * @return string|null
     */
    public function getNumber() :  ? string {
        return $this->number;
    }

    /**
     * @return string|null
     */
    public function getRarity() :  ? string {
        return $this->rarity;
    }

    /**
     * @return array|null
     */
    public function getResistances() :  ? array
    {
        return $this->resistances;
    }

    /**
     * @return array|null
     */
    public function getRetreatCost() :  ? array
    {
        return $this->retreatCost;
    }

    /**
     * @return array|null
     */
    public function getRules() :  ? array
    {
        return $this->rules;
    }

    /**
     * @return Set
     */
    public function getSet() : Set {
        return $this->set;
    }

    /**
     * @return array|null
     */
    public function getSubtypes():  ? array
    {
        return $this->subtypes;
    }

    /**
     * @return string|null
     */
    public function getSupertype() :  ? string {
        return $this->supertype;
    }

    /**
     * @return TCGPlayer|null
     */
    public function getTcgplayer() :  ? TCGPlayer {
        return $this->tcgplayer;
    }

    /**
     * @return array|null
     */
    public function getTypes() :  ? array
    {
        return $this->types;
    }

    /**
     * @return array|null
     */
    public function getWeaknesses() :  ? array
    {
        return $this->weaknesses;
    }

    /**
     * @param array|null $abilities
     */
    public function setAbilities(  ? array $abilities ) {
        $this->abilities = $abilities;
    }

    /**
     * @param AncientTrait|null $ancientTrait
     */
    public function setAncientTrait(  ? AncientTrait $ancientTrait ) {
        $this->ancientTrait = $ancientTrait;
    }

    /**
     * @param string|null $artist
     */
    public function setArtist(  ? string $artist ) {
        $this->artist = $artist;
    }

    /**
     * @param array|null $attacks
     */
    public function setAttacks(  ? array $attacks ) {
        $this->attacks = $attacks;
    }

    /**
     * @param  CardMarket|null  $cardMarket
     */
    public function setCardmarket(  ? CardMarket $cardMarket ) : void{
        $this->cardmarket = $cardMarket;
    }

    /**
     * @param int|null $convertedRetreatCost
     */
    public function setConvertedRetreatCost(  ? int $convertedRetreatCost ) {
        $this->convertedRetreatCost = $convertedRetreatCost;
    }

    /**
     * @param string|null $evolvesFrom
     */
    public function setEvolvesFrom(  ? string $evolvesFrom ) {
        $this->evolvesFrom = $evolvesFrom;
    }

    /**
     * @param array|null $evolvesTo
     */
    public function setEvolvesTo(  ? array $evolvesTo ) {
        $this->evolvesTo = $evolvesTo;
    }

    /**
     * @param string|null $flavorText
     */
    public function setFlavorText(  ? string $flavorText ) {
        $this->flavorText = $flavorText;
    }

    /**
     * @param string|null $hp
     */
    public function setHp(  ? string $hp ) {
        $this->hp = $hp;
    }

    /**
     * @param string $id
     */
    public function setId( string $id ) {
        $this->id = $id;
    }

    /**
     * @param CardImages|null $images
     */
    public function setImages(  ? CardImages $images ) {
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
     * @param array|null $nationalPokedexNumbers
     */
    public function setNationalPokedexNumbers(  ? array $nationalPokedexNumbers ) {
        $this->nationalPokedexNumbers = $nationalPokedexNumbers;
    }

    /**
     * @param string|null $number
     */
    public function setNumber(  ? string $number ) {
        $this->number = $number;
    }

    /**
     * @param string|null $rarity
     */
    public function setRarity(  ? string $rarity ) {
        $this->rarity = $rarity;
    }

    /**
     * @param array|null $resistances
     */
    public function setResistances(  ? array $resistances ) {
        $this->resistances = $resistances;
    }

    /**
     * @param array|null $retreatCost
     */
    public function setRetreatCost(  ? array $retreatCost ) {
        $this->retreatCost = $retreatCost;
    }

    /**
     * @param array|null $rules
     */
    public function setRules(  ? array $rules ) {
        $this->rules = $rules;
    }

    /**
     * @param Set $set
     */
    public function setSet( Set $set ) {
        $this->set = $set;
    }

    /**
     * @param array|null $subtypes
     */
    public function setSubtypes(  ? array $subtypes ) {
        $this->subtypes = $subtypes;
    }

    /**
     * @param string|null $supertype
     */
    public function setSupertype(  ? string $supertype ) {
        $this->supertype = $supertype;
    }

    /**
     * @param TCGPlayer|null $tcgplayer
     */
    public function setTcgplayer(  ? TCGPlayer $tcgplayer ) {
        $this->tcgplayer = $tcgplayer;
    }

    /**
     * @param array|null $types
     */
    public function setTypes(  ? array $types ) {
        $this->types = $types;
    }

    /**
     * @param array|null $weaknesses
     */
    public function setWeaknesses(  ? array $weaknesses ) {
        $this->weaknesses = $weaknesses;
    }
}