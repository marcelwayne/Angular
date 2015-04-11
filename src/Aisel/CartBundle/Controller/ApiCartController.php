<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Frontend API controller to for Cart CRUD
 *
 * @author Ivan Proskoryakov <volgodark@gmail.com>
 */
class ApiCartController extends Controller
{

    /**
     * Cart manager
     */
    private function getCartManager()
    {
        return $this->get('aisel.cart.manager');
    }

    /**
     * User manager
     */
    private function getUserManager()
    {
        return $this->get('frontend.user.manager');
    }

    /**
     * cartAction
     *
     */
    public function cartAction()
    {
        $user = $this->getUserManager()->getUser();

        return $this->getCartManager()->getUserCart($user);
    }

    /**
     * /%frontend_api%/cart/product/{productId}/qty/{qty}/add.json
     *
     * @param int $productId
     * @param int $qty
     *
     * @return JsonResponse $response
     */
    public function cartProductAddAction($productId, $qty)
    {
        $user = $this->getUserManager()->getUser();
        $cartItem = $this->getCartManager()->addProductToCart($user, $productId, $qty);

        if ($cartItem) {
            $response = array('status' => true, 'message' => 'Product added to cart', 'cartItem' => $cartItem);
        } else {
            $response = array('status' => false, 'message' => 'Something went wrong during adding product to cart');
        }

        return $response;
    }

    /**
     * /%frontend_api%/cart/product/{productId}/qty/{qty}/update.json
     *
     * @param int $productId
     * @param int $qty
     *
     * @return JsonResponse $response
     */
    public function cartProductUpdateAction($productId, $qty)
    {
        $user = $this->getUserManager()->getUser();
        $cartItem = $this->getCartManager()->updateProductInCart($user, $productId, $qty);

        if ($cartItem) {
            $response = array('status' => true, 'message' => 'Cart updated', 'cartItem' => $cartItem);
        } else {
            $response = array('status' => false, 'message' => 'Something went wrong during removing product from cart');
        }

        return $response;
    }

}
