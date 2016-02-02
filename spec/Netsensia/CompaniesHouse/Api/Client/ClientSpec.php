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
    
    /**
     * Get the basic company information
     * 
     * https://developer.companieshouse.gov.uk/api/docs/company/company_number/readCompanyProfile.html
     */
    function it_can_get_a_company_profile()
    {
        $this->beConstructedWith(getApiKey());
        
        $this->getCompanyProfile('06236637')->registered_office_address->address_line_1->shouldBe('57 London Road');
    }
    
    function it_can_get_a_list_of_officers_for_a_company()
    {
        $this->beConstructedWith(getApiKey());
    
        $this->getOfficerList('06236637')->start_index->shouldBe(0);
        $this->getOfficerList('06236637')->kind->shouldBe('officer-list');
        $this->getOfficerList('01436733', 2, 1, 'appointed_on')->start_index->shouldBe(1);
    }
}
