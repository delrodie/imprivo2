// assets/controllers/user_actions_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static values = {
        editUrl: String,
        deleteUrl: String,
        confirmMessage: { type: String, default: "Voulez-vous vraiment supprimer cet élément ?" },
        successMessage: { type: String, default: "Opération effectuée avec succès !" },
        errorMessage: { type: String, default: "Une erreur est survenue." }
    }

    connect() {
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
                const offcanvasBody = document.querySelector("#offcanvasDevis .offcanvas-body")
                offcanvasBody.innerHTML = html

                const offcanvas = new bootstrap.Offcanvas(document.getElementById("offcanvasDevis"))
                offcanvas.show()
            })
            .catch(() => alert(this.errorMessageValue))
    }

    delete(event) {
        event.preventDefault()
        if (!confirm(this.confirmMessageValue)) return

        fetch(this.deleteUrlValue, {
            method: "DELETE",
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
            .then(response => {
                if (response.ok) {
                    const table = $('#tabList').DataTable()
                    table.ajax.reload()
                    alert(this.successMessageValue)
                } else {
                    alert(this.errorMessageValue)
                }
            })
            .catch(() => alert(this.errorMessageValue))
    }
}
