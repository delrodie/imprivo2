// assets/controllers/user_actions_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static values = {
        editUrl: String,
        deleteUrl: String
    }

    connect() {
        // Pour debug
        console.log("UserActionsController connecté")
    }

    edit(event) {
        event.preventDefault()
        const url = this.editUrlValue

        fetch(url, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .then(response => response.text())
            .then(html => {
                // Injecter le formulaire dans ton offcanvas
                const offcanvasBody = document.querySelector("#offcanvasDevis .offcanvas-body")
                offcanvasBody.innerHTML = html

                // Ouvrir l’offcanvas via Bootstrap
                const offcanvas = new bootstrap.Offcanvas(document.getElementById("offcanvasDevis"))
                offcanvas.show()
            })
    }

    delete(event) {
        event.preventDefault()
        if (!confirm("Voulez-vous vraiment supprimer cet utilisateur ?")) return

        fetch(this.deleteUrlValue, {
            method: "DELETE",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .then(response => {
                if (response.ok) {
                    // Rafraîchir DataTable sans recharger la page
                    const table = $('#tabList').DataTable()
                    table.ajax.reload()
                } else {
                    alert("Erreur lors de la suppression")
                }
            })
    }
}
