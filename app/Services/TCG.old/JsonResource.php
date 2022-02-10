<?php

namespace App\Services\TCG;

use App\Models\TCG\Pokemon;
use App\Services\TCG\Interfaces\ResourceInterface;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use stdClass;

/**
 * Class JsonResource
 *
 * @package Pokemon\Resources
 */
class JsonResource implements ResourceInterface {
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Inflector
     */
    protected $inflector;

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var string
     */
    protected $resource;

    /**
     * Request constructor.
     *
     * @param string $resource
     * @param array $options
     * @param string|null $apiKey
     */
    public function __construct( $resource, array $options = [],  ? string $apiKey = null ) {
        \Debugbar::info( $apiKey );

        $defaults = [
            'base_uri' => Pokemon::API_URL,
            'verify'   => false,
        ];

        if ( ! empty( $apiKey ) ) {
            $defaults['headers'] = [
                'X-Api-Key' => $apiKey,
            ];
        }

        $this->resource  = $resource;
        $this->client    = new Client( array_merge( $defaults, $options ) );
        $this->inflector = InflectorFactory::create()->build();
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function all() : array
    {
        $response = $this->getResponseData( $this->client->send( $this->prepare() ) );
        \Debugbar::info( $response );

        return $this->transformAll( $response );
    }

    /**
     * @param ResponseInterface $response
     *
     * @return stdClass
     */
    protected function getResponseData( ResponseInterface $response ): stdClass {
        return json_decode( $response->getBody()->getContents() );
    }

    /**
     * @return Request
     */
    protected function prepare(): Request {
        return new Request( $this->method, $this->resource );
    }

    /**
     * @param stdClass $response
     *
     * @return array
     */
    protected function transformAll( stdClass $response ): array
    {
        return $response->data;
    }
}