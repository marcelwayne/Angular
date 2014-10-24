<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\SearchBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Frontend Search REST API controller
 *
 * @author Ivan Proskoryakov <volgodark@gmail.com>
 */
class ApiController extends Controller
{

    /**
     * Is User Authenticated
     *
     * @return boolean
     */
    private function isAuthenticated()
    {
        return $this->get('frontend.user.manager')->isAuthenticated();
    }

    /**
     * @Rest\View
     * /api/search/?query=abc
     */
    public function searchAction(Request $request)
    {
        $params = array(
            'current' => $request->get('current'),
            'limit' => $request->get('limit'),
            'query' => $request->get('query'),
            'order' => $request->get('order'),
            'orderby' => $request->get('orderby')
        );

        if (!$params['query']) return false;

        if ($request->get('user') && $this->isAuthenticated()) {
            $userid = $this->get('security.context')->getToken()->getUser()->getId();
            $params['userid'] = $userid;
        }
        $searchResult = $this->container->get("aisel.search.manager")->search($params);
        return $searchResult;

    }

}
