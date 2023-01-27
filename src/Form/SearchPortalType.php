<?php

namespace App\Form;

use App\Entity\Data\SearchData;
use App\Entity\Portal;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class SearchPortalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search...',
                    'class' => 'form-control search-input',
                ],
            ])
            ->add('portals', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Portal::class,
                'multiple' => true,
                'attr' => [
                    'data-choices' => 'choices'
                ]
            ])
            ->add('page', HiddenType::class, [ 'label' => false,'required' => false, 'mapped' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
