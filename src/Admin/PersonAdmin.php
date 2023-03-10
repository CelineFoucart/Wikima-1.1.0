<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Category;
use App\Entity\Person;
use App\Entity\PersonType;
use App\Entity\Portal;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\TemplateType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class PersonAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('firstname')
            ->add('lastname')
            ->add('fullname')
            ->add('nationality')
            ->add('job')
            ->add('birthday')
            ->add('birthdayPlace')
            ->add('deathDate')
            ->add('deathPlace')
            ->add('parents')
            ->add('type')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('firstname')
            ->add('lastname')
            ->add('nationality')
            ->add('birthday')
            ->add('deathDate')
            ->add('type')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'read' => ['template' => 'Admin/show.html.twig'],
                    'show' => [],
                    'image' => ['template' => 'Admin/components/image_action.html.twig'],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->tab('Generality')
                ->with('Identity', ['class' => 'col-md-6 hidden-header'])
                    ->add('firstname', TextType::class, [
                        'attr' => [
                            'data-type' => 'firstname',
                        ],
                    ])
                    ->add('lastname', TextType::class, [
                        'attr' => [
                            'data-type' => 'lastname',
                        ],
                    ])
                    ->add('fullname', TextType::class, [
                        'attr' => [
                            'data-type' => 'fullname',
                        ],
                    ])
                    ->add('slug', TextType::class, [
                        'attr' => [
                            'data-target' => 'slug-character',
                        ],
                    ])
                    ->add('physicalDescription', TextareaType::class, [
                        'required' => false,
                        'attr' => [
                            'style' => 'height: 73px'
                        ]
                    ])
                    ->add('species')
                    ->add('gender')
                    ->add('preview', TemplateType::class, [
                        'template' => 'Admin/components/_preview.html.twig',
                        'label' => false,
                    ])
                ->end()
                ->with('Informations', ['class' => 'col-md-6 hidden-header'])
                    ->add('nationality')
                    ->add('job')
                    ->add('portals', EntityType::class, [
                        'class' => Portal::class,
                        'choice_label' => 'title',
                        'multiple' => true,
                        'required' => false,
                    ])
                    ->add('categories', EntityType::class, [
                        'class' => Category::class,
                        'choice_label' => 'title',
                        'multiple' => true,
                        'required' => false,
                    ])
                    ->add('type', EntityType::class, [
                        'class' => PersonType::class,
                        'choice_label' => 'title',
                        'multiple' => true,
                        'required' => false,
                    ])                    
                    ->add('description', TextareaType::class, [
                        'required' => false,
                        'attr' => [
                            'style' => 'height: 73px'
                        ],
                        'help' => 'help_description',
                    ])
                    ->add('isSticky', null, [
                        'required' => false,
                    ])
                ->end()
            ->end()
            
            ->tab('Relations')
                ->with('Relations', ['class' => 'hidden-header'])
                    ->add('parents')
                    ->add('siblings')
                    ->add('partner')
                    ->add('children')
                ->end()
            ->end()
            ->tab('Biography')
                ->with('Full biography', ['class' => 'col-md-8'])
                    ->add('presentation', CKEditorType::class, [
                        'config' => ['toolbar' => 'full', 'format_tags' => 'p;h3;h4;h5;h6;pre'],
                    ])
                    ->add('biography', CKEditorType::class, [
                        'config' => ['toolbar' => 'full', 'format_tags' => 'p;h3;h4;h5;h6;pre'],
                        'required' => false,
                    ])
                ->end()
                ->with('In short', ['class' => 'col-md-4'])
                    ->add('birthday')
                    ->add('birthdayPlace')
                    ->add('deathDate')
                    ->add('deathPlace')
                ->end()
            ->end()

            ->tab('Personality')
                ->with('Personality', ['class' => 'hidden-header'])
                    ->add('personality', CKEditorType::class, [
                        'config' => ['toolbar' => 'full', 'format_tags' => 'p;h3;h4;h5;h6;pre'],
                        'required' => false,
                    ])
                ->end()
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('In Short', ['class' => 'col-md-6'])
                ->add('id')
                ->add('fullname', null, [
                    'template' => 'Admin/person/_person_name.html.twig',
                ])
                ->add('slug')
                ->add('nationality')
                ->add('type')
                ->add('job')
                ->add('birthday')
                ->add('birthdayPlace')
                ->add('deathDate')
                ->add('deathPlace')
                ->add('description')
                ->add('image', null, ['template' => 'Admin/person/image_show.html.twig'])
            ->end()
            ->with('Relations', ['class' => 'col-md-6'])
                ->add('parents')
                ->add('siblings')
                ->add('partner')
                ->add('children')
            ->end()
            ->with('Full Presentation', ['class' => 'col-md-12'])
                ->add('presentation', null, [
                    'safe' => true,
                ])
                ->add('biography', null, [
                    'safe' => true,
                ])
                ->add('personality', null, [
                    'safe' => true,
                ])
            ->end()
        ;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('image', $this->getRouterIdParameter().'/image');
    }
}
