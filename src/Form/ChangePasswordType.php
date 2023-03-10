<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [ //Grisé : on ne veut que changer le "Password"
                'disabled' => true,
                'label'=>'Mon adresse email'
            ])
            ->add('firstname', TextType::class, [ //Grisé : on ne veut que changer le "Password"
                'disabled' => true,
                'label'=>'Mon prénom'
            ])
            ->add('lastname', TextType::class, [ //Grisé : on ne veut que changer le "Password"
                'disabled' => true,
                'label'=>'Mon nom'
            ])    
            ->add('old_password', PasswordType::class,[
                'label'=>'Mon mot de passe actuel',
                'mapped' => false, //Ne pas lier ce champ à l'entité User,sinon : "erreur symfony".
                'attr'=> [
                    'placeholder'=>'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class,[ //Les 2 champs pour le MDP et La vérif du MDP
                'type' => PasswordType::class,      //sont gérés
                'mapped' => false, //Ne pas lier ce champ à l'entité User,sinon : "erreur symfony".
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => 'Mon nouveau mot de passe',
                'required'=> true,
                'first_options'  => ['label' => 'Mon nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre mot de passe'
                ]],
                'second_options' => ['label' => 'Confirmez votre nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Merci de confirmer votre nouveau mot de passe'
                ]]
            ])
            ->add('submit', SubmitType::class,[
                'label' => "Mettre à jour"  
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void //injection de dépendance
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
