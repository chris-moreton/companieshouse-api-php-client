<?php
namespace Netsensia\CompaniesHouse\Api\Client\Common\Guzzle;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Guzzle Client with our own defaults.
 *
 * Class Client
 */
class Client extends GuzzleClient {

    public function __construct(array $config = []) {

        parent::__construct( $config );

        $this->setDefaultOption( 'exceptions', false );
        $this->setDefaultOption( 'allow_redirects', false );
        

    }

    /**
     * Sets the token as a default header value for the client.
     *
     * @param $apiKey
     */
    public function setApiKey( $apiKey ) {

        $this->setDefaultOption( 'auth', [$apiKey, ''] );

    }

} // class
