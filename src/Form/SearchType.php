<?php //Formulaire qui ne sera pas lié à une entité

namespace App\Form;

use App\Classe\Search;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType
{
  //Construction des propriétés du formulaire
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder//Ajout des infos des 2 champs de saisie du formulaire
      //Toutes ces infos ne sont pas visible avec le formulaire dans la vue de twig.
      ->add('string', TextType::class, [ //String --> Vient de la classe Search.php
        'label' => 'Rechercher',
        'required' => false,
        'attr' => [
        'placeholder' => 'Votre recherche...',
        'class' => 'form-control-sm',//Classe de Bootstrap --> Input un peu plus petit 
        ]
      ])
      ->add('categories', EntityType::class, [ //categories --> Vient de la classe Search.php
        'label' => false,   //"Entitype" permet de lier un input à une entité déja créé
        'required' => false,
        'class' => Category::class, //Entité 'Category' sélecionné avec cet input.
        'multiple' => true,
        'expanded' => true //Possibilité de sélectionner plusieurs valeur
      ])
      ->add('submit',SubmitType::class,[
        'label'=>'Filtrer',
        'attr'=>[
          'class'=>'btn-block btn-info' //Class de Bootstrap
        ]
      ])
      ;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Search::class, //de la classe créé manuellement dans src/Classe/Search.php
      'method' => 'GET', //Transmission des infos par le formulaire
      'crsf_protection' => false, //Désactication de la protection des forms par Symfony
    ]);
  }

  public function getBlockPrefix() : string //Par défaut, retourne un tableau avec les valeur dedans
  {
    return '';  //Retourne rien, mais fonction obligatoire
  }

  
}
