<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' => ['class'=> 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => 'Nom de famille <span>*</span>',
                'label_attr' =>['class' => 'form-label'],
                'label_html' => true,
                'required' => true
            ])
            ->add('prenom', TextType::class,[
                'attr' => ['class'=> 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => 'Prénom(s)  <span>*</span>',
                'label_attr' =>['class' => 'form-label'],
                'label_html' => true,
                'required' => true
            ])
            ->add('fonction', TextType::class,[
                'attr' => ['class'=> 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => 'Fonction <span>*</span>',
                'label_attr' =>['class' => 'form-label'],
                'label_html' => true,
                'required' => true
            ])
            ->add('telephone', TelType::class,[
                'attr' => ['class'=> 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => 'Téléphone <span>*</span>',
                'label_attr' =>['class' => 'form-label'],
                'label_html' => true,
                'required' => true
            ])
//            ->add('code')
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('updatedAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('createdBy')
//            ->add('updatedBy')
//            ->add('user', EntityType::class, [
//                'class' => User::class,
//                'choice_label' => 'email',
//                'attr' => ['class' => 'form-select form-control-sm'],
//                'label' => "Adresse email",
//                'allow_add' => true,
//                'allow_delete' => true
//            ])
            ->add('user', UserType::class,[
                'label' => false,
                'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
