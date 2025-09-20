<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Devis;
use App\Entity\Representant;
use App\Enum\DevisStatut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('numero')
            ->add('date', DateType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete' => 'off'],
                'widget' => 'single_text',
                'label' => "Date",
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('totalHT', NumberType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete' => "off", 'readonly' => true],
                'label' => "Total HT",
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('totalTVA', NumberType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete' => "off", 'readonly' => true],
                'label' => "Total TVA",
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('totalTTC', NumberType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete' => "off", 'readonly' => true],
                'label' => "Total TTC",
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('tauxTVA', IntegerType::class,[
                'attr' => [
                    'class' => 'form-control form-control-sm', 'autocomplete' => "off",
                    'step' => '0.01',
                    'min' => 0,
//                    'data-devis-target' => 'tauxTVA',
//                    'data-action' => 'input->devis#updateTotals'
                ],
                'label' => "Taux TVA (%)",
                'label_attr' => ['class' => 'form-label']
            ])
//            ->add('statut', EnumType::class,[
//                'class' => DevisStatut::class,
//                'label' => "Statut",
//                'label_attr' => ['class' => 'form-label']
//            ])
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('updatedAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('createdBy')
//            ->add('updatedBy')
            ->add('noteInterne', TextareaType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => "Note interne",
                'label_attr' => ['class' => 'form-label'],
                'required' => false
            ])
            ->add('noteClient', TextareaType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => "Note au client",
                'label_attr' => ['class' => 'form-label'],
                'required' => false
            ])
            ->add('remise', IntegerType::class,[
                'attr' =>[
                    'class' => 'form-control form-control-sm',
                    'autocomplete' => 'off',
                    'step' => '1',
                    'min' => 0,
                ],
                'label' => 'Remise',
                'label_attr' => ['class' => 'form-label'],
                'required' => false
            ])
            ->add('client', ClientAutocompleteField::class, [
                'class' => Client::class,
                'choice_label' => 'nom',
                'label' => "Client",
                'label_attr' =>['class' => 'form-label'],
                'placeholder' => "-- Selectionnez le client --",
//                'attr' => ['class' => 'form-select form-select-sm'],
            ])
            ->add('contactClient', EntityType::class, [
                'class' => Representant::class,
                'choice_label' => 'nom',
                'label' => "Representant client",
                "label_attr" => ['class' => 'form-label'],
                "placeholder" => "-- Selectionnez le client du client --",
                'attr' => ['class' => 'form-select form-select-sm']
            ])
            ->add('lignes', CollectionType::class,[
                'entry_type' => DevisLigneType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
