<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Representant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepresentantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' => ['class' => 'form-control from-control-sm', 'autocomplete'=>'off'],
                'label' => "Nom de famille <span>*</span>",
                'label_html' => true,
                'label_attr' =>['class' =>'form-label'],
                'required' => true
            ])
            ->add('prenom', TextType::class,[
                'attr' => ['class' => 'form-control from-control-sm', 'autocomplete'=>'off'],
                'label' => "Prénoms <span>*</span>",
                'label_html' => true,
                'label_attr' =>['class' =>'form-label'],
                'required' => true
            ])
            ->add('fonction', TextType::class,[
                'attr' => ['class' => 'form-control from-control-sm', 'autocomplete'=>'off'],
                'label' => "Fonction <span>*</span>",
                'label_html' => true,
                'label_attr' =>['class' =>'form-label'],
                'required' => true
            ])
            ->add('telephone1', TextType::class,[
                'attr' => ['class' => 'form-control from-control-sm', 'autocomplete'=>'off'],
                'label' => "Numero de telephone 1<span>*</span>",
                'label_html' => true,
                'label_attr' =>['class' =>'form-label'],
                'required' => true
            ])
            ->add('telephone2', TextType::class,[
                'attr' => ['class' => 'form-control from-control-sm', 'autocomplete'=>'off'],
                'label' => "Numero de telephone 2",
                'label_html' => true,
                'label_attr' =>['class' =>'form-label'],
                'required' => false
            ])
            ->add('email', TextType::class,[
                'attr' => ['class' => 'form-control from-control-sm', 'autocomplete'=>'off'],
                'label' => "Adresse email <span>*</span>",
                'label_html' => true,
                'label_attr' =>['class' =>'form-label'],
                'required' => true
            ])
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('updatedAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('createdBy')
//            ->add('updatedBy')
            ->add('client', ClientAutocompleteField::class, [
//                'class' => Client::class,
//                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select form-select-sm'],
                'label' => 'Client <span>*</span>',
                'label_html' => true,
                'label_attr' => ['class' => 'form-label'],
                'placeholder' => "-- Selectionnez le client --"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Representant::class,
        ]);
    }
}
