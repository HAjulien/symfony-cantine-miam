<?php

namespace App\Form;

use App\Entity\Feature;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FeatureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('paragraphe', CKEditorType::class, [
                'attr' => [
                    'class' => 'ckeditor',
                ]
            ])
            ->add('image')
            ->add('altImage')
            ->add('buton')
            ->add('chemin', ChoiceType::class, [
                'choices' => [
                    'about' => '/about',
                    'équipe' => '/equipe',
                    'plat' => '/plat',
                    'click & collect' => '/clickcollet'

                ],
            ]
            )
            ->add('Valider', SubmitType::class, array(
                'attr' => ['class' => 'btn b1'],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feature::class,
        ]);
    }
}
