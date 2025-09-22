<?php

namespace App\Form;

use App\Entity\Devis;
use App\Entity\DevisLigne;
use App\Entity\UniteMesure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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
                'attr' => [
                    'class' => 'form-control form-control-sm text-center',
                    'autocomplete'=>'off',
                    'step' => '1',
                    'min' => 0,
                    'value' => 1
                ],
                'label' => "Quantité",
            ])
            ->add('prixUnitaire', IntegerType::class,[
                'attr' => [
                    'class' => 'form-control form-control-sm text-center',
                    'autocomplete'=>'off',
                    'step' => '5',
                    'min' => 25,
//                    'value' => 100
                ],
                'label' => "P. Unitaire",
            ])
            ->add('montant', NumberType::class,[
                'attr' => [
                    'class' => 'form-control form-control-sm text-end',
                    'autocomplete'=>'off',
                    'readonly' => true,
                ],
                'label' => "Montant",
            ])
//            ->add('details')
//            ->add('devis', EntityType::class, [
//                'class' => Devis::class,
//                'choice_label' => 'id',
//            ])
            ->add('uom', UomAutocompleteField::class, [
//                'class' => UniteMesure::class,
//                'choice_label' => 'libelle',
//                'label' => "Unité de mesure",
//                'required' => false,
//                'placeholder' => "-- Selectionnez --",
//                'autocomplete' => true
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
