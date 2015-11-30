<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\ProductBundle\Entity;

use Aisel\ReviewBundle\Entity\Review as BaseReview;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Review
 *
 * @author Ivan Proskuryakov <volgodark@gmail.com>
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="aisel_product_review")
 * @ORM\Entity(repositoryClass="Aisel\ResourceBundle\Repository\CollectionRepository")
 * @JMS\ExclusionPolicy("all")
 */
class Review extends BaseReview
{

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Aisel\ReviewBundle\Entity\Node")
     * @ORM\JoinTable(
     *     name="aisel_product_review_node",
     *     joinColumns={@ORM\JoinColumn(name="review_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="node_id", referencedColumnName="id")}
     * )
     * @JMS\Type("ArrayCollection<Aisel\ReviewBundle\Entity\Node>")
     * @JMS\Expose
     */
    protected $nodes;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="Aisel\ProductBundle\Entity\Product", inversedBy="products")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     * @JMS\Type("Aisel\ProductBundle\Entity\Product")
     * @JMS\Expose
     */
    private $product;

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

}
