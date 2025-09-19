import { Controller } from "@hotwired/stimulus";

// Connects to data-controller="devis"
export default class extends Controller {
    static targets = [
        "ligne",
        "totalHT",
        "totalTVA",
        "totalTTC",
        "remise",
        "tauxTVA"
    ]

    connect() {
        // Retarder le calcul pour s'assurer que tous les targets sont rendus
        setTimeout(() => this.updateTotals(), 0)
    }

    updateLine(event) {
        const row = event.target.closest("tr")
        if (!row) return

        const qtyInput = row.querySelector("input[name*='quantite']")
        const priceInput = row.querySelector("input[name*='prixUnitaire']")
        const montantInput = row.querySelector("input[name*='montant']")

        const qty = parseFloat(qtyInput?.value) || 0
        const price = parseFloat(priceInput?.value) || 0
        const montant = qty * price

        if (montantInput) montantInput.value = montant.toFixed(2)

        this.updateTotals()
    }

    updateTotals() {
        let totalHT = 0

        // Calcul du total HT des lignes
        this.ligneTargets.forEach(row => {
            const montantInput = row.querySelector("input[name*='montant']")
            totalHT += parseFloat(montantInput?.value) || 0

            console.log(`Lignes dans updateTotals ${totalHT}`)
        })

        console.log(`Hors Ligne ${totalHT}`)

        // Remise
        const remise = this.hasRemiseTarget ? parseFloat(this.remiseTarget.value) || 0 : 0
        const baseHT = totalHT - remise

        // TVA
        const taux = this.hasTauxTVATarget ? parseFloat(this.tauxTVATarget.value) || 0 : 0
        const totalTVA = baseHT * (taux / 100)
        const totalTTC = baseHT + totalTVA

        // Mise à jour des champs
        if (this.hasTotalHTTarget) this.totalHTTarget.value = totalHT.toFixed(2)
        if (this.hasTotalTVATarget) this.totalTVATarget.value = totalTVA.toFixed(2)
        if (this.hasTotalTTCTarget) this.totalTTCTarget.value = totalTTC.toFixed(2)
    }
}
