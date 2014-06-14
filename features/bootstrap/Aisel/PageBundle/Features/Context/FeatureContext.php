<?php

namespace Aisel\PageBundle\Features\Context;

use Aisel\ResourceBundle\Features\Context\DefaultContext;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Behat context class.
 */
class FeatureContext extends DefaultContext
{

    /**
     * @When /^Script access api_aisel_pagelist route$/
     */
    public function goToPageListURI()
    {
        $this->getSession()->visit($this->generateUrl('api_aisel_pagelist'));
        $this->assertSession()->statusCodeEquals(200);
    }

    /**
     * @Given /^Content should contain valid JSON$/
     */
    public function jsonHasTotal()
    {
        $json = $this->getSession()->getPage()->getContent();
        $pageDetails = json_decode($json);
        assertNotEmpty($pageDetails->total);
    }

}
