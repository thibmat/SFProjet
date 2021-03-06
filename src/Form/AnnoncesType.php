<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $options['categories'];
        dump($categories);
        $builder
            ->add('category', ChoiceType::class, [
                'choices'=>$categories,
                'label'=>'Categorie'
                ])
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
                ImageType::class,[
                    'mapped'=>false,
                    'label'=>false,
                    'attr' => ['class' => 'dropzone']
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
            'categories'=> ''
        ]);
    }
}
