<?php

namespace App\Form;

use App\Entity\Client;
use App\Enum\ClientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('code')
            ->add('nom', TextType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Nom (Raison sociale) <span>*</span>",
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true,
                'required' => true
            ])
            ->add('telephone', TelType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Telephone <span>*</span>",
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true,
                'required' => true
            ])
            ->add('email', EmailType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Adresse email",
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true,
                'required' => false
            ])
            ->add('rc', TextType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Régistre de commerce",
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true,
                'required' => false
            ])
            ->add('adresse', TextareaType::class,[
                'attr' => ['class' => 'form-control'],
                'label' => "Adresse géographique <span>*</span>",
                'required' => true,
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true
            ])
            ->add('ville', TextType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Ville/Commune <span>*</span>",
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true,
                'required' => true
            ])
            ->add('type', EnumType::class,[
                'class' => ClientType::class,
                'label' => 'Type de client <span>*</span>',
                'placeholder' => "Type de client",
                'attr' => ['class' => 'form-select form-select-sm'],
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true
            ])
            ->add('actif', CheckboxType::class,[
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-label'],
                'required' => false
            ])
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('updatedAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('createdBy')
//            ->add('updatedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
