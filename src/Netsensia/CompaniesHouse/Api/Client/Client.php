<?php
namespace Netsensia\CompaniesHouse\Api\Client;

use GuzzleHttp\Client as GuzzleClient;

use GuzzleHttp\Psr7\Response ;


class Client {
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
   * @param string $apiKey The API key
   */
  public function __construct(
    $apiKey = NULL
  ) {
    $this->apiKey = $apiKey;
  }

  /**
   * Returns the GuzzleClient.
   *
   * @return GuzzleClient
   */
  private function client() {

    if (!isset($this->guzzleClient)) {
      $this->guzzleClient = new GuzzleClient([
        'base_uri' => $this->apiBaseUri,
        'auth' => array($this->apiKey, ''),
        'headers' => array(
          'exceptions' => false,
          'allow_redirects' => false,
        )
      ]);
    }

    return $this->guzzleClient;

  }

  /**
   * Company profile
   *
   * https://developer.companieshouse.gov.uk/api/docs/company/company_number/company_number.html
   *
   * @param $companyNumber The company number of the officer list being requested
   *
   * @return boolean|mixed
   */
  public function getCompanyProfile($companyNumber) {
    $response = $this->client()
      ->get('/company/' . $companyNumber);

    if ($response->getStatusCode() != 200) {
      return $this->log($response, FALSE);
    }

    $jsonDecode = json_decode($response->getBody());
    $this->log($response, TRUE);

    return $jsonDecode;
  }

  /**
   * List the company officers
   *
   * https://developer.companieshouse.gov.uk/api/docs/company/company_number/officers/officerList.html
   *
   * @param $companyNumber The company number of the officer list being requested
   * @param $itemsPerPage Optional. The number of officers to return per page
   * @param $startIndex Optional. The offset into the entire result set that this page starts.
   * @param $orderBy
   *        Optional
   *        The field by which to order the result set. Possible values are: appointed_on resigned_on surname.
   *        Negating the order_by will reverse the order. For example, order_by=-surname will give results in descending order of surname.
   *
   * @return boolean|mixed
   */
  public function getOfficerList($companyNumber, $itemsPerPage = NULL, $startIndex = NULL, $orderBy = NULL) {
    $response = $this->client()
      ->get('/company/' . $companyNumber . '/officers', [
        'query' => [
          'items_per_page' => $itemsPerPage,
          'start_index' => $startIndex,
          'order_by' => $orderBy,
        ],
      ]);

    if ($response->getStatusCode() != 200) {
      return $this->log($response, FALSE);
    }

    $jsonDecode = json_decode($response->getBody());

    $this->log($response, TRUE);

    return $jsonDecode;
  }

  /**
   * Search for a company
   *
   * https://developer.companieshouse.gov.uk/api/docs/search/companies/companysearch.html
   *
   * @param $q The search term
   * @param $itemsPerPage Optional. The number of officers to return per page
   * @param $startIndex Optional. The offset into the entire result set that this page starts.
   *
   * @return boolean|mixed
   */
  public function companySearch($q, $itemsPerPage = NULL, $startIndex = NULL) {
    $response = $this->client()->get('/search/companies', [
      'query' => [
        'q' => $q,
        'items_per_page' => $itemsPerPage,
        'start_index' => $startIndex,
      ],
    ]);

    if ($response->getStatusCode() != 200) {
      return $this->log($response, FALSE);
    }

    $jsonDecode = json_decode($response->getBody());

    $this->log($response, TRUE);

    return $jsonDecode;
  }

  /**
   * @return the $isError
   */
  public function isError() {
    return $this->isError;
  }

  /**
   * @param boolean $isError
   */
  public function setIsError($isError) {
    $this->isError = $isError;
  }

  /**
   * @return the $apiKey
   */
  public function getApiKey() {
    return $this->apiKey;
  }

  /**
   * @param string $apiKey
   */
  public function setApiKey($apiKey) {
    $this->apiKey = $apiKey;
  }

  /**
   * @return the $lastStatusCode
   */
  public function getLastStatusCode() {
    return $this->lastStatusCode;
  }

  /**
   * @param number $lastStatusCode
   */
  public function setLastStatusCode($lastStatusCode) {
    $this->lastStatusCode = $lastStatusCode;
  }

  /**
   * @return the $lastContent
   */
  public function getLastContent() {
    return $this->lastContent;
  }

  /**
   * @param string $lastContent
   */
  public function setLastContent($lastContent) {
    $this->lastContent = $lastContent;
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
  public function log(Response $response, $isSuccess = TRUE) {
    $this->lastStatusCode = $response->getStatusCode();

    $responseBody = (string) $response->getBody();
    $jsonDecoded = json_decode($responseBody, TRUE);

    if (json_last_error() == JSON_ERROR_NONE) {
      $this->lastContent = $jsonDecoded;
    }
    else {
      $this->lastContent = $responseBody;
    }

    // @todo - Log properly
    if (!$isSuccess) {
    }

    $this->setIsError(!$isSuccess);

    return $isSuccess;
  }

}
