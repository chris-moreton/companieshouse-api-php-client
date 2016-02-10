# Companies House API PHP Client

You will need an API key to use this client. To get an API key, go to https://developer.companieshouse.gov.uk/api/docs/index/gettingStarted/apikey_authorisation.html

Add to project using Composer
-----------------------------

    "require" : {
      "netsensia/companieshouse-api-php-client" : "~0.1.0"
    },
    
Usage
-----

    $client = new Client($apiKey);
    $profile = $client->getCompanyProfile($companyNumber);
    $officerList = $client->getOfficerList($companyNumber);
    
    $profile->getCompanyProfile('06236637')->registered_office_address->address_line_1;
    
A full list of available calls can be found by examining the ClientSpec.php file which contains the spec tests. The *json* directory contains examples of the data returned by the Companies House API.

Development
-----------

### Clone the repo and compose

    git clone git@github.com:netsensia/companieshouse-api-php-client
    cd companieshouse-api-php-client
    php composer.phar install

### Run the tests

Create a file called .apiKey in the root of the project and add your companies house api key to it.

    bin/phpspec run --format=pretty -vvv --stop-on-failure
