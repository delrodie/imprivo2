import { Controller } from "@hotwired/stimulus";

// Connects to data-controller="representant"
export default class extends Controller {
    static targets = ["client", "representant"]

    connect() {
        console.log("Controller representant connecté");

        // Si un client est déjà sélectionné (mode édition), charger ses représentants
        if (this.clientTarget.value) {
            this.update().then(() => {
                // Re-sélectionner le représentant déjà choisi si présent
                const currentValue = this.representantTarget.dataset.current;
                if (currentValue) {
                    this.representantTarget.value = currentValue;
                }
            });
        }
    }

    async update() {
        const clientId = this.clientTarget.value;

        // reset
        this.representantTarget.innerHTML = "<option value=''>-- Sélectionnez --</option>";

        if (clientId) {
            try {
                const response = await fetch(`/api/client/${clientId}/representants`);
                if (!response.ok) throw new Error("Erreur API");

                const data = await response.json();

                data.forEach(rep => {
                    const option = document.createElement("option");
                    option.value = rep.id;
                    option.textContent = rep.nom;
                    this.representantTarget.appendChild(option);
                });
            } catch (e) {
                console.error("Erreur lors du chargement des représentants", e);
            }
        }
    }
}
