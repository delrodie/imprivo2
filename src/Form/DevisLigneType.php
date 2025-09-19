<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\DevisLigne;
use App\Entity\UniteMesure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisLigneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Désignation",
            ])
            ->add('quantite', IntegerType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Quantité",
            ])
            ->add('prixUnitaire', IntegerType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "P. Unitaire",
            ])
            ->add('montant', IntegerType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off', 'readonly' => true],
                'label' => "Montant",
            ])
//            ->add('details')
//            ->add('devis', EntityType::class, [
//                'class' => Devis::class,
//                'choice_label' => 'id',
//            ])
            ->add('uom', EntityType::class, [
                'class' => UniteMesure::class,
                'choice_label' => 'libelle',
                'label' => "Unité de mesure",
                'required' => false,
                'placeholder' => "-- Selectionnez --",
                'autocomplete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DevisLigne::class,
        ]);
    }
}
