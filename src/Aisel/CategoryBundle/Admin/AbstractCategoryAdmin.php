<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\CategoryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Validator\ErrorElement;

/**
 * Category CRUD configuration for Backend
 *
 * @author Ivan Proskoryakov <volgodark@gmail.com>
 */
class AbstractCategoryAdmin extends Admin
{
    protected $categoryManager;
    protected $baseRoutePattern = 'category';
    protected $maxPerPage = 500;
    protected $maxPageLinks = 500;
    protected $categoryEntity = 'Aisel\PageBundle\Entity\Category';

    public function setManager($categoryManager)
    {
        $this->categoryManager = $categoryManager;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('title')
            ->assertNotBlank()
            ->end()
            ->with('description')
            ->assertNotBlank()
            ->end()
            ->with('metaUrl')
            ->assertNotBlank()
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->orderBy('o.root', 'ASC');
        $query->addOrderBy('o.lft', 'ASC');
        $query->addOrderBy('o.title', 'ASC');

        return $query;
    }

    public function getFormTheme()
    {
        return array('AiselAdminBundle:Form:form_admin_fields.html.twig');
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $subject = $this->getSubject();
        $id = $subject->getId();
        $formMapper
            ->with('General')
            ->add('title', 'text', array('label' => 'Title'))
            ->add('description', 'ckeditor',
                array(
                    'label' => 'Content',
                    'required' => true,
                ))
            ->add('locale', 'aisel_locale', array('label' => 'aisel.page.locale', 'attr' => array('class' => 'form-control')))
            ->add('status', 'choice', array('choices' => array(
                '0' => 'Disabled',
                '1' => 'Enabled'),
                'label' => 'aisel.page.status', 'attr' => array('class' => 'form-control')))
            ->add('parent', 'aisel_gedmotree', array('expanded' => true, 'multiple' => false,
                'class' => $this->categoryEntity,
                'query_builder' => function ($er) use ($id) {
                        $qb = $er->createQueryBuilder('p');
                        if ($id) {
                            $qb->where('p.id <> :id')->setParameter('id', $id);
                        }
                        $qb->orderBy('p.root, p.lft', 'ASC');

                        return $qb;
                    }, 'empty_value' => 'no parent'

            ))

            ->with('Metadata')
            ->add('metaUrl', 'text', array('label' => 'Url', 'help' => 'note: URL value must be unique'))
            ->add('metaTitle', 'text', array('label' => 'Title', 'required' => false))
            ->add('metaDescription', 'textarea', array('label' => 'Description', 'required' => false))
            ->add('metaKeywords', 'textarea', array('label' => 'Keywords', 'required' => false))
            ->with('Dates')
            ->add('createdAt', 'datetime', array('label' => 'Created At','disabled' => true, 'attr' => array()))
            ->add('updatedAt', 'datetime', array('label' => 'Updated At', 'attr' => array()))
            ->end();

    }

    public function prePersist($category)
    {
        $url = $category->getMetaUrl();
        $normalUrl = $this->categoryManager->normalizeCategoryUrl($url);

        $category->setMetaUrl($normalUrl);
        $category->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $category->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
    }

    public function preUpdate($category)
    {
        $url = $category->getMetaUrl();
        $categoryId = $category->getId();
        $normalUrl = $this->categoryManager->normalizeCategoryUrl($url, $categoryId);

        $category->setMetaUrl($normalUrl);
        $category->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
    }

    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('aisel.category.information')
            ->add('id', null, array('label' => 'aisel.page.id'))
            ->add('locale', null, array('label' => 'aisel.page.locale'))
            ->add('status', 'boolean', array('label' => 'aisel.page.status'))
            ->with('aisel.category.meta')
            ->add('metaUrl', null, array('label' => 'aisel.page.url'))
            ->add('metaTitle', null, array('label' => 'aisel.category.meta_title'))
            ->add('metaDescription', null, array('label' => 'aisel.category.meta_description'))
            ->add('metaKeywords', null, array('label' => 'aisel.category.meta_keywords'))
            ->with('aisel.category.general')
            ->with('aisel.category.dates')
            ->add('createdAt', null, array('label' => 'aisel.category.created_at'))
            ->add('updatedAt', null, array('label' => 'aisel.category.updated_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function toString($object)
    {
        return $object->getId() ? $object->getTitle() : $this->trans('link_add', array(), 'SonataAdminBundle');
    }

}
