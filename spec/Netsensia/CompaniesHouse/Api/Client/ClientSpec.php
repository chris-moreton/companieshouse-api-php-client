<?php
namespace spec\Netsensia\CompaniesHouse\Api\Client;

include "spec/SpecHelper.php";

use PhpSpec\ObjectBehavior;

class ClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Netsensia\CompaniesHouse\Api\Client\Client');
    }
    
    function it_can_get_details_of_a_company()
    {
        $this->beConstructedWith(getApiKey());
        
        $this->getCompanyDetails('06236637')->company_name->shouldBe('NETSENSIA LIMITED');
    }
}
