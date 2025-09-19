<?php

namespace App\Form;

use App\Entity\UniteMesure;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniteMesureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class,[
                'attr' =>['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Code interne<span>*</span>",
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true
            ])
            ->add('libelle', TextType::class,[
                'attr' =>['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Libellé <span>*</span>",
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true
            ])
            ->add('symbole', TextType::class,[
                'attr' =>['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Symbole <span>*</span>",
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true
            ])
            ->add('actif', CheckboxType::class,[
                'attr' => ['class' => 'form-check-input'],
                'label' => "Actif",
                'label_attr' => ['class' => 'form-check-label'],
                'required' => false
            ])
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('updatedAt', null, [
//                'widget' => 'single_text',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UniteMesure::class,
        ]);
    }
}
