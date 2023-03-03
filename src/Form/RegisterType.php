<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName',TextType::class,[
                'label' => 'Prénom',
                'constraints' => [new Length(['min' => 4, 'max' => 30])],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre prénom'
                ]
            ])
            ->add('lastName',TextType::class,[
                'label' => 'Nom',
                'constraints' => [new Length(['min' => 4, 'max' => 30])],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre Nom'
                ]
            ])
            ->add('email',EmailType::class,[
                'label' => 'Email',
                'constraints' => [new Length(['min' => 5, 'max' => 40])],
                'attr' => [
                    'placeholder' => 'Merci de saisir votre Email'
                ]
            ])
         
            ->add('password', RepeatedType::class,[ //Les 2 champs pour le MDP et La vérif du MDP
                'type' => PasswordType::class,      //sont gérés
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => 'Votre mot de passe',
                'required'=> true,
                'first_options'  => ['label' => 'Votre mot de passe',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre mot de passe'
                ]],
                'second_options' => ['label' => 'Confirmez votre mot de passe',
                'attr' => [
                    'placeholder' => 'Merci de confirmer votre mot de passe'
                ]],
                'constraints' => [new Length(['min' => 4, 'max' => 20])]
                
            ])
						           
		    ->add('submit', SubmitType::class,[
                'label' => "S'inscrire"  
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
