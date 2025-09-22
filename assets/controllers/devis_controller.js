import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["ligne","totalHT","totalTVA","totalTTC","remise","tauxTVA", "form", "submitButton"]

    connect() {
        setTimeout(() => this.updateTotals(), 0)

        // 🔥 écoute l’événement venant de collection_controller
        this.element.addEventListener("ligne:changed", () => {
            this.updateTotals()
        })
    }

    updateLine(event) {
        const row = event.target.closest("tr")
        if (!row) return

        const qty = parseFloat(row.querySelector("input[name*='quantite']")?.value) || 0
        const price = parseFloat(row.querySelector("input[name*='prixUnitaire']")?.value) || 0

        const montantHidden = row.querySelector("[data-devis-target='montant']")
        const montantDisplay = row.querySelector("[data-devis-target='montantDisplay']")

        const montant = this.roundTo5(qty * price)

        // Champ caché pour Symfony
        if (montantHidden) {
            montantHidden.value = montant
        }

        // Champ affiché pour l’utilisateur
        if (montantDisplay) {
            montantDisplay.value = this.formatNumber(montant)
        }

        this.updateTotals()
    }

    updateTotals() {
        let sommeLignes = 0
        this.ligneTargets.forEach(row => {
            const montantHidden = row.querySelector("[data-devis-target='montant']")
            const rawValue = parseFloat(montantHidden?.value) || 0
            sommeLignes += rawValue
        })

        const remise = this.hasRemiseTarget ? parseFloat(this.remiseTarget.value.replace(/\s/g, "")) || 0 : 0
        const baseHT = sommeLignes - remise

        const taux = this.hasTauxTVATarget ? parseFloat(this.tauxTVATarget.value) || 0 : 0
        const totalTVA = this.roundTo5(baseHT * (taux / 100))
        const totalTTC = this.roundTo5(baseHT + totalTVA)

        if (this.hasTotalHTTarget) {
            this.totalHTTarget.value = sommeLignes
            this.totalHTTarget.nextElementSibling.value = this.formatNumber(this.roundTo5(sommeLignes))
        }

        if (this.hasTotalTVATarget) {
            this.totalTVATarget.value = totalTVA
            this.totalTVATarget.nextElementSibling.value = this.formatNumber(totalTVA)
        }

        if (this.hasTotalTTCTarget) {
            this.totalTTCTarget.value = totalTTC
            this.totalTTCTarget.nextElementSibling.value = this.formatNumber(totalTTC)
        }
    }

    roundTo5(value) {
        return Math.ceil(value / 5) * 5
    }

    formatNumber(value) {
        return value.toLocaleString("fr-FR", { minimumFractionDigits: 0 })
    }
}
