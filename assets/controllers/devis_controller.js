import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["ligne","totalHT","totalTVA","totalTTC","remise","tauxTVA"]

    connect() {
        setTimeout(() => this.updateTotals(), 0)

        // 🔥 Écoute l'événement venant de collection_controller
        this.element.addEventListener("ligne:changed", () => {
            this.updateTotals()
        })
    }

    updateLine(event) {
        const row = event.target.closest("tr")
        if (!row) return

        const qty = parseFloat(row.querySelector("input[name*='quantite']")?.value) || 0
        const price = parseFloat(row.querySelector("input[name*='prixUnitaire']")?.value) || 0
        const montantInput = row.querySelector("input[name*='montant']")

        if (montantInput) {
            const montant = this.roundTo5(qty * price)
            montantInput.value = this.formatNumber(montant)
            montantInput.dataset.raw = montant
        }
        this.updateTotals()
    }

    updateTotals() {
        let sommeLignes = 0
        this.ligneTargets.forEach(row => {
            const rawValue = parseFloat(row.querySelector("input[name*='montant']")?.dataset.raw)
                || parseFloat(row.querySelector("input[name*='montant']")?.value.replace(/\s/g, ""))
                || 0
            sommeLignes += rawValue
        })

        const remise = this.hasRemiseTarget ? parseFloat(this.remiseTarget.value.replace(/\s/g, "")) || 0 : 0
        const baseHT = sommeLignes - remise

        const taux = this.hasTauxTVATarget ? parseFloat(this.tauxTVATarget.value) || 0 : 0
        const totalTVA = this.roundTo5(baseHT * (taux / 100))
        const totalTTC = this.roundTo5(baseHT + totalTVA)

        if (this.hasTotalHTTarget) this.totalHTTarget.value = this.formatNumber(this.roundTo5(sommeLignes))
        if (this.hasTotalTVATarget) this.totalTVATarget.value = this.formatNumber(totalTVA)
        if (this.hasTotalTTCTarget) this.totalTTCTarget.value = this.formatNumber(totalTTC)
    }

    roundTo5(value) {
        return Math.ceil(value / 5) * 5
    }

    formatNumber(value) {
        return value.toLocaleString("fr-FR", { minimumFractionDigits: 0 })
    }
}
