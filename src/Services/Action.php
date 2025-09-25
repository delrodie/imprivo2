<?php

namespace App\Services;

class Action
{
    const DEVIS_CREE = 'Crée';
    const DEVIS_BROUILLON = 'Brouillon';
    const DEVIS_ENVOYE = 'Envoyé';
    const DEVIS_VALIDE = 'Validé';
    const DEVIS_REFUSE = 'Réfusé';
    const DEVIS_TRANSFORME = 'Transformé';

    // FACTURE
    const FACTURE_CREE = 'Créée';
    const FACTURE_BROUILLON = 'Brouillon';
    const FACTURE_EMETTRE = 'Emise';
    const FACTURE_VALIDE = 'Validée';
    const FACTURE_PARTIELLE = "Partiellement payée";
    const FACTURE_PAYEE = "Payée";
    const FACTURE_IMPAYEE = "Impayée";
    const FACTURE_SOLDEE = "Soldée";
    const FACTURE_ANNULEE = "Annulée";
}
