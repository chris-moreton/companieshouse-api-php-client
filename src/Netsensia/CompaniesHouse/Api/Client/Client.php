<?php
namespace Netsensia\CompaniesHouse\Api\Client;

use Opg\Lpa\Api\Client\Common\Guzzle\Client as GuzzleClient;

use GuzzleHttp\Message\Response;


class Client
{
    /**
     * The base URI for the API
     */
    private $apiBaseUri = 'https://apiv2';
    
    /**
     * The API auth token
     * 
     * @var string
     */
    private $token;
    
    /**
     * 
     * @var GuzzleClient
     */
    private $guzzleClient;
    
    /**
     * The status code from the last API call
     * 
     * @var number
     */
    private $lastStatusCode;
    
    /**
     * The content body from the last API call
     * 
     * @var string
     */
    private $lastContent;
    
    /**
     * Did the last API call return with an error?
     * 
     * @var boolean
     */
    private $isError;
    
    /**
     * @return the $apiBaseUri
     */
    public function getApiBaseUri()
    {
        return $this->apiBaseUri;
    }

    /**
     * @return the $authBaseUri
     */
    public function getAuthBaseUri()
    {
        return $this->authBaseUri;
    }

    /**
     * @param field_type $apiBaseUri
     */
    public function setApiBaseUri($apiBaseUri)
    {
        $this->apiBaseUri = $apiBaseUri;
    }

    /**
     * @param field_type $authBaseUri
     */
    public function setAuthBaseUri($authBaseUri)
    {
        $this->authBaseUri = $authBaseUri;
    }

    /**
     * Create an API client for the given uri endpoint.
     * 
     * Optionally pass in a previously-obtained token. If no token is provided,
     * you will need to call the authenticate(...) function
     * 
     * @param string $token  The API auth token
     */
    public function __construct(
        $token = null
    )
    {
        $this->setToken($token);

    }

    /**
     * Returns the GuzzleClient.
     *
     * If a authentication token is available it will be preset in the HTTP header.
     *
     * @return GuzzleClient
     */
    private function client()
    {

        if( !isset($this->guzzleClient) ){
            $this->guzzleClient = new GuzzleClient();
        }

        if( $this->getToken() != null ){
            $this->guzzleClient->setToken( $this->getToken() );
        }

        return $this->guzzleClient;

    }
  
    /**
     * Log the response of the API call and set some internal member vars
     * If content body is JSON, convert it to an array
     * 
     * @param Response $response
     * @param bool $isSuccess
     * @return boolean
     * 
     * @todo - External logging
     */
    public function log(Response $response, $isSuccess=true)
    {
        $this->setLastStatusCode($response->getStatusCode());

        $responseBody = (string)$response->getBody();
        $jsonDecoded = json_decode($responseBody, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            $this->setLastContent($jsonDecoded);
        } else {
            $this->setLastContent($responseBody);
        }

        // @todo - Log properly
        if (!$isSuccess) { 
        }
        
        $this->setIsError(!$isSuccess);

        return $isSuccess;
    }
}
