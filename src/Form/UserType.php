<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class,[
                'attr' => ['class' => 'form-control form-control-sm', 'autocomplete'=>'off'],
                'label' => "Adresse email  <span>*</span>",
                'constraints' => [
                    new Assert\NotBlank(['message' => "L'adresse email est obligatoire"]),
                    new Assert\Email(['message' => 'L\'adresse {{ value }} n\'est pas une adresse valide. '])
                ],
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true

            ])
            ->add('roles', ChoiceType::class,[
                'attr' => ['class' => 'form-check-input'],
                'choices' => [
                    'Super administrateur' => 'ROLE_SUPER_ADMIN',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER'
                ],
                'multiple'=> true,
                'expanded'=>true,
                'constraints' => [
                    new Assert\NotBlank(['message' => "Veuillez choisir un rôle pour cet utilisateur."])
                ],
                'label_attr' => ['class' => 'form-label']
            ])
            ->add('password', PasswordType::class,[
                'attr' => ['class' => 'form-control form-control-sm'],
                'label' => "Mot de passe <span>*</span>",
                'constraints' => [
                    new Assert\NotBlank(['message' => "Le mot de passe ne peut être vide."])
                ],
                'label_attr' => ['class' => 'form-label'],
                'label_html' => true
            ])
//            ->add('connexion')
//            ->add('lastConnectedAt', null, [
//                'widget' => 'single_text',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
