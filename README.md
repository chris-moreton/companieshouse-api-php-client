# Companies House API PHP Client

Development
-----------

### Clone the repo and compose

    git clone git@github.com:netsensia/companieshouse-api-php-client
    cd companieshouse-api-php-client
    php composer.phar install

### Run the tests

Create a file called .apiKey and add your companies house api key to it. To get an API key, go to https://developer.companieshouse.gov.uk/api/docs/index/gettingStarted/apikey_authorisation.html

    bin/phpspec run --format=pretty -vvv --stop-on-failure