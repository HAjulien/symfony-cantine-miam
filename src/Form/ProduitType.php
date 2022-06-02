<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description', CKEditorType::class, [
                'attr' => [
                    'class' => 'ckeditor',
                ]
            ])
            //->add('createAt')
            ->add('prixVente', NumberType::class)
            ->add('prixAchat', NumberType::class)
            ->add('image')
            ->add('altImage')
            ->add('selectionner', CheckboxType::class, array(
                'required' => false,
                'value' => 1,
            ))
            ->add('category', EntityType::class, [
                'class' => Category::class
            ])
            //->add('slug')
            ->add('Valider', SubmitType::class, array(
                'attr' => ['class' => 'btn b1'],
            )) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
