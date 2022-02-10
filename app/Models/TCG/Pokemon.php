<?php

namespace App\Models\TCG;

use App\Services\TCG\Interfaces\QueriableResourceInterface;
use App\Services\TCG\Interfaces\ResourceInterface;
use App\Services\TCG\JsonResource;
use App\Services\TCG\QueryableResource;

/**
 * Class Pokemon
 *
 * @package Pokemon
 */
class Pokemon {
    const API_URL = 'https://api.pokemontcg.io/v2/';

    const ASCENDING_ORDER = 1;

    const DESCENDING_ORDER = -1;

    /**
     * @var string|null
     */
    private static $apiKey = null;

    /**
     * @var null|array
     */
    private static $cache = [
        'resources' => [],
        'options'   => [],
    ];

    /**
     * @var array
     */
    private static $options = [];

    /**
     * @param string|null $apiKey
     */
    public static function ApiKey(  ? string $apiKey ) {
        self::$apiKey = $apiKey;
    }

    /**
     * @return QueriableResourceInterface
     */
    public static function Card() : QueriableResourceInterface {
        //\Debugbar::info( self::getQueriableResource( 'cards' ) );
        return self::getQueriableResource( 'cards' );
    }

    /**
     * @param array $options
     */
    public static function Options( array $options ) {
        self::$options = $options;
    }

    /**
     * @return ResourceInterface
     */
    public static function Rarity(): ResourceInterface {
        return self::getJsonResource( 'rarities' );
    }

    /**
     * @return QueriableResourceInterface
     */
    public static function Set(): QueriableResourceInterface {
        return self::getQueriableResource( 'sets' );
    }

    /**
     * @return ResourceInterface
     */
    public static function Subtype(): ResourceInterface {
        return self::getJsonResource( 'subtypes' );
    }

    /**
     * @return ResourceInterface
     */
    public static function Supertype(): ResourceInterface {
        return self::getJsonResource( 'supertypes' );
    }

    /**
     * @return ResourceInterface
     */
    public static function Type(): ResourceInterface {
        return self::getJsonResource( 'types' );
    }

    /**
     * @param string $type
     * @return ResourceInterface
     */
    private static function getJsonResource( $type ): ResourceInterface {
        if ( ! array_key_exists( $type, self::$cache['resources'] ) || self::haveOptionsBeenUpdated( $type, self::$options ) ) {
            self::$cache['options'][$type]   = self::$options;
            self::$cache['resources'][$type] = new JsonResource( $type, self::$options, self::$apiKey );
        }
        return self::$cache['resources'][$type];
    }

    /**
     * @param string $type
     * @return QueriableResourceInterface
     */
    private static function getQueriableResource( $type ): QueriableResourceInterface {
        if ( ! array_key_exists( $type, self::$cache['resources'] ) || self::haveOptionsBeenUpdated( $type, self::$options ) ) {
            self::$cache['options'][$type]   = self::$options;
            self::$cache['resources'][$type] = new QueryableResource( $type, self::$options, self::$apiKey );
        }

        return self::$cache['resources'][$type];
    }

    /**
     * @param string $key
     * @param array $options
     * @return bool
     */
    private static function haveOptionsBeenUpdated( $key, array $options = [] ): bool {
        if ( array_key_exists( $key, self::$cache ) ) {
            return ( self::$cache[$key] != $options );
        }
        return false;
    }
}