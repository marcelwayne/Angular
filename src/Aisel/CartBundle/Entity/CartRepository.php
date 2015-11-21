<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\CartBundle\Entity;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Aisel\CartBundle\Entity\Cart;
use Aisel\ProductBundle\Entity\Product;
use Aisel\FrontendUserBundle\Entity\FrontendUser;

/**
 * CartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartRepository extends DocumentRepository
{

    /**
     * Add product to cart
     *
     * @param FrontendUser $user
     * @param Product $product
     * @param int $qty
     *
     * @return Cart $cartItem
     */
    public function addProduct($user, $product, $qty)
    {
        $em = $this->getEntityManager();

        if ($cartItem = $this->findProduct($user, $product)) {
            $originalQty = $cartItem->getQty();
            $newQty = $originalQty + $qty;
            $cartItem->setQty($newQty);
        } else {
            $cartItem = new Cart();
            $cartItem->setFrontenduser($user);
            $cartItem->setProduct($product);
            $cartItem->setQty($qty);
        }
        $em->persist($cartItem);
        $em->flush();

        return $cartItem;
    }

    /**
     * Update product in cart
     *
     * @param FrontendUser $user
     * @param Product $product
     * @param int $qty
     *
     * @return Cart $total
     */
    public function updateProduct($user, $product, $qty = null)
    {
        $em = $this->getEntityManager();
        $cartItem = $this->findProduct($user, $product);

        // if cart item exists
        if ($cartItem) {
            if ($qty) {
                $cartItem->setQty($qty);
                $em->persist($cartItem);
                $em->flush();
            } else {
                $em->remove($cartItem);
                $em->flush();
            }
        }

        return $cartItem;
    }

    /**
     * Find product in cart
     *
     * @param FrontendUser $user
     * @param Product $product
     *
     * @return Cart $cartItem
     */
    public function findProduct($user, $product)
    {
        $query = $this
            ->getEntityManager()
            ->createQueryBuilder('Aisel\CartBundle\Entity\Cart')
            ->field('product')->equals($product->getId())
            ->field('frontenduser')->equals($user->getId());

        $cartItem = $query
            ->getQuery()
            ->execute()
            ->toArray();

        if ($cartItem) return $cartItem[0];
        return false;
    }

}
