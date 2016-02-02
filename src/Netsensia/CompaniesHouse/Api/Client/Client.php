<?php
namespace Netsensia\CompaniesHouse\Api\Client;

use Netsensia\CompaniesHouse\Api\Client\Common\Guzzle\Client as GuzzleClient;

use GuzzleHttp\Message\Response;


class Client
{
    /**
     * The base URI for the API
     */
    private $apiBaseUri = 'https://api.companieshouse.gov.uk';
    
    /**
     * 
     * @var GuzzleClient
     */
    private $guzzleClient;
    
    /**
     * 
     * @var string
     */
    private $apiKey;
    
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
     * Create an API client for the given uri endpoint.
     * 
     * @param string $apiKey  The API key
     */
    public function __construct(
        $apiKey = null
    )
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Returns the GuzzleClient.
     *
     * @return GuzzleClient
     */
    private function client()
    {

        if( !isset($this->guzzleClient) ){
            $this->guzzleClient = new GuzzleClient();
        }

        $this->guzzleClient->setApiKey( $this->apiKey );

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
        $this->lastStatusCode = $response->getStatusCode();

        $responseBody = (string)$response->getBody();
        $jsonDecoded = json_decode($responseBody, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            $this->lastContent = $jsonDecoded;
        } else {
            $this->lastContent = $responseBody;
        }

        // @todo - Log properly
        if (!$isSuccess) { 
        }
        
        $this->setIsError(!$isSuccess);

        return $isSuccess;
    }

    /**
     * @return the $isError
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * @param boolean $isError
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;
    }

    /**
     * @return the $apiKey
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return the $lastStatusCode
     */
    public function getLastStatusCode()
    {
        return $this->lastStatusCode;
    }

    /**
     * @param number $lastStatusCode
     */
    public function setLastStatusCode($lastStatusCode)
    {
        $this->lastStatusCode = $lastStatusCode;
    }

    /**
     * @return the $lastContent
     */
    public function getLastContent()
    {
        return $this->lastContent;
    }

    /**
     * @param string $lastContent
     */
    public function setLastContent($lastContent)
    {
        $this->lastContent = $lastContent;
    }

    public function getCompanyDetails($companyNumber)
    {
        $response = $this->client()->get($this->apiBaseUri . '/company/' . $companyNumber);
        
        if( $response->getStatusCode() != 200 ){
            return $this->log($response, false);
        }
        
        $jsonDecode = json_decode($response->getBody());
        
        $this->log($response, true);
        
        return $jsonDecode;
    }
}
