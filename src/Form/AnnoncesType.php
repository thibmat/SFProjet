<?php

namespace App\Form;

use App\Entity\Annonces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category')
            ->add('annonceTitre', null, [
                'label'=>'Titre de l\'annonce'
            ])
            ->add('annonceTexte', null, [
                'label'=>'Texte de l\'annonce'
            ])
            ->add('annoncePrix', null, [
                'label'=>'Prix'
                ])
            ->add(
                'imageFile',
                FileType::class,
                [
                    'label' => 'Image (jpg / png / jpeg)',
                    'mapped'=>false
                ]
            )
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
