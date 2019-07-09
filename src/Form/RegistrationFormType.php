<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userName',null,[
                'label' => false,
                'attr'=>[
                    'placeholder'=>'Nom d\'utilisateur'
                    ]
            ])
            ->add('userMail', null,[
                'label' => false,
                'attr'=>[
                    'placeholder'=>'Email'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label'=>false,
                'attr'=>[
                    'placeholder'=>'Mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez spécifier un mot de passe valide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter plus de {{ limit }} caractères',
                        // max length allowed by Symfony for user reasons
                        'max' => 4096,
                    ]),
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
