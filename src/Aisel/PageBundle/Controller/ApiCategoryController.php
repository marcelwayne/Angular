<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\PageBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Category REST API for Frontend
 *
 * @author Ivan Proskoryakov <volgodark@gmail.com>
 */
class ApiCategoryController extends Controller
{

    /**
     * @Rest\View
     * /api/page/category/list.json?limit=2&current=3
     */
    public function categoryListAction(Request $request, $locale)
    {
        $params = array(
            'current' => $request->query->get('current'),
            'limit' => $request->query->get('limit'),
        );
        $categoryList = $this->container->get("aisel.pagecategory.manager")->getCategories($params, $locale);
        return $categoryList;
    }

    /**
     * @Rest\View
     * /api/page/category/tree.json
     */
    public function categoryTreeAction(Request $request, $locale)
    {
        $categoryList = $this->container->get("aisel.pagecategory.manager")->getCategoryTree($locale);
        return $categoryList;
    }

    /**
     * @Rest\View
     * /api/page/category/view/{$$urlKey}.json
     */
    public function categoryViewAction($urlKey, $locale)
    {
        $category = $this->container->get("aisel.pagecategory.manager")->getCategoryByUrl($urlKey, $locale);
        return $category;
    }

}
