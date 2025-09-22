<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class ClientAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Client::class,
            'searchable_fields' => ['nom','telephone'],
            'label' => 'Client',
            'choice_label' => 'nom',
            'constraints' =>[
                new NotNull(message: 'Veuillez sélectionnez un client')
            ],
            'placeholder' => "-- Selectionnez le client --",
            'options_as_html' => true,
            'allow_options_create' => true,
            'min_characters' => 3,
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
