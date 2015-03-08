<?php

namespace Aisel\OrderBundle\Features\Context\Api;

use Aisel\ResourceBundle\Features\Context\DefaultContext;

/**
 * Behat context class.
 */
class FeatureContext extends DefaultContext
{

    /**
     * @When /^Script access api_aisel_orderlist route$/
     */
    public function scriptAccessRoute()
    {
        $this->getSession()->visit($this->generateUrl('api_aisel_orderlist'));
        $content = $this->getSession()->getPage()->getContent();
        $this->assertSession()->statusCodeEquals(200);
    }

}
